FROM php:8.1-apache-bullseye

WORKDIR /var/www/html

# dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl \
    && docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/src|' /etc/apache2/sites-available/000-default.conf \
&& sed -i 's|AllowOverride None|AllowOverride All|' /etc/apache2/apache2.conf


COPY . /var/www/html

# permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80

CMD ["apache2-foreground"]
