# Use official PHP with Apache
FROM php:8.2-apache

# 1. Install required PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip bcmath \
    && rm -rf /var/lib/apt/lists/*

# (libonig-dev is no longer needed for PHP 8.2)

# 2. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 3. Set Apache DocumentRoot to /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
      /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/var/www/html/public|g' \
      /etc/apache2/apache2.conf

# 4. Copy only composer files first (better layer caching)
COPY composer.json composer.lock /var/www/html/
WORKDIR /var/www/html

# 5. Install Composer and allow it to run as root
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 6. Install PHP dependencies but skip scripts for now
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --no-scripts

# 7. Copy the rest of the application
COPY . /var/www/html/

# 8. Run Laravel package discovery now that the full app is present
RUN php artisan package:discover --ansi

# 9. Fix permissions
RUN mkdir -p public/uploads \
    && chown -R www-data:www-data public/uploads storage bootstrap/cache \
    && chmod -R 775 public/uploads storage bootstrap/cache

EXPOSE 10000
CMD ["apache2-foreground"]
