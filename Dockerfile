FROM php:8.2-apache

# Install system packages & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd intl bcmath \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache DocumentRoot to Laravel's public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Copy only composer files first for caching
COPY composer.json composer.lock /var/www/html/
WORKDIR /var/www/html

# Install Composer (from official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy the rest of the application
COPY . /var/www/html/

# Set permissions
RUN mkdir -p public/uploads \
 && chown -R www-data:www-data storage bootstrap/cache public/uploads \
 && chmod -R 775 public/uploads

# (Optional) Cache config/routes for production
# RUN php artisan config:cache && php artisan route:cache

# Expose Render's expected port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]
