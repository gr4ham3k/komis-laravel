FROM php:8.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libpq-dev netcat-openbsd

RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath xml ctype

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

EXPOSE 8000
