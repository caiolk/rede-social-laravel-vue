FROM php:7.2.23-fpm-alpine3.9

RUN apk add bash nano nginx supervisor curl zlib zlib-dev && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.9.0 && \
    chmod +x /usr/local/bin/composer


RUN adduser --disabled-password --gecos "" --no-create-home www && mkdir -p /run/nginx

COPY ./docker-config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY ./docker-config/api-nginx.conf /etc/nginx/conf.d
COPY ./docker-config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#RUN composer install
#RUN chmod -R 777 /var/www/html/storage /var/www/html/bootstrap
#RUN php artisan cache:clear


EXPOSE 8000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]