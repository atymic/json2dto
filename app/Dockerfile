FROM composer:1.9.1 as build
WORKDIR /app
COPY . /app
RUN composer global require hirak/prestissimo && composer install

FROM php:7.3-apache-stretch

EXPOSE 8080
COPY --from=build /app /var/www/
COPY docker/json2dto.conf /etc/apache2/sites-available/json2dto.conf
RUN chmod 777 -R /var/www/storage/ && \
    echo "Listen 8080" >> /etc/apache2/ports.conf && \
    chown -R www-data:www-data /var/www/ && \
    a2enmod rewrite
