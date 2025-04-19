FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . /var/www/html/

# Set permissions for writable directories
RUN chown -R www-data:www-data /var/www/html/application/cache \
                               /var/www/html/application/logs \
                               /var/www/html/uploads \
    && chmod -R 755 /var/www/html/application/cache \
                   /var/www/html/application/logs \
                   /var/www/html/uploads

# Expose port 80
EXPOSE 80 