# Use official PHP with Apache
FROM php:8.2-apache

# Install required system packages and PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd intl bcmath

# Enable Apache mod_rewrite (needed for Laravel routes)
RUN a2enmod rewrite

# Set Apache DocumentRoot to /var/www/html/public (Laravel entry point)
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Copy app code
COPY . /var/www/html/

# Create uploads folder and set permissions
RUN mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 775 /var/www/html/public/uploads

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Laravel dependencies (with unlimited memory to avoid OOM errors on Render free tier)
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader --verbose

# Set permissions for Laravel storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Render's required port
EXPOSE 10000

# Start Apache
CMD ["apache2-foreground"]
