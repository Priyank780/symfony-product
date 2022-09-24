# docker/php-fpm/Dockerfile

FROM php:8.1.2-fpm-alpine3.15

COPY php.ini "/usr/local/etc/php/php.ini"

RUN apk --update --no-cache add openssh autoconf g++ make icu-dev dcron freetype libpng libjpeg-turbo freetype-dev libpng-dev libjpeg-turbo-dev cmake libuv libuv-dev libressl-dev gmp-dev openssl pcre-dev git rabbitmq-c-dev


RUN pecl install -o -f redis apcu amqp pcov && \
    pecl clear-cache && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable pcov.so redis.so apcu.so amqp.so
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j${NPROC} gd && \
    apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql opcache intl

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/product

CMD crond; php-fpm

EXPOSE 9000
