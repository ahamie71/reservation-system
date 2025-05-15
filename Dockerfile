FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

# Apache pointe vers le dossier public de Symfony
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
