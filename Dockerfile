FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    libzip-dev \
    autoconf \
    build-base \
    inotify-tools \
    && docker-php-ext-install zip pdo_mysql

RUN pecl install swoole-5.1.2 && docker-php-ext-enable swoole

WORKDIR /var/www

EXPOSE 8000

# CMD ["php", "public/server.php"]