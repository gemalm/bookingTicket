# Use the official PHP image with Apache
FROM php:8.0-apache

# Install SQLite3 client tools and required PHP extensions
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo pdo_sqlite

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html

# Enable URL rewriting
RUN a2enmod rewrite

# Set permissions (optional, for development)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80