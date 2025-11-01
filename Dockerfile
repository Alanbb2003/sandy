# PHP with Apache
FROM php:8.2-apache

# Enable PHP extensions required by Laravel
RUN apt-get update && \
    apt-get install -y zip unzip git && \
    docker-php-ext-install pdo_mysql

# Enable Apache rewrite (required for Laravel routing)
RUN a2enmod rewrite

# Copy project files into the container
COPY . /var/www/html

# Set document root to Laravel public folder
WORKDIR /var/www/html
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Fix Apache configuration for Laravel public folder
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/*.conf

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

EXPOSE 80
CMD ["apache2-foreground"]
