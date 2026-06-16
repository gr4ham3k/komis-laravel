FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev libcurl4-openssl-dev netcat-openbsd \
    nginx supervisor \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath xml ctype curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/ && \
    mkdir -p /var/log/supervisor

EXPOSE 8000
