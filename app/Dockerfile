FROM composer:2 as build

WORKDIR /app
COPY . /app

RUN composer install

FROM php:7.4-apache

RUN apt-get update
RUN apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

EXPOSE 8080
COPY --from=build /app /var/www/
COPY docker/json2dto.conf /etc/apache2/sites-available/000-default.conf

RUN echo "Listen 8080" >> /etc/apache2/ports.conf && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite
