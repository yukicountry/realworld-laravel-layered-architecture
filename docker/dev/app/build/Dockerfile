FROM php:8.2-fpm

RUN apt update && \
    apt install -y libpq-dev zip unzip && \
    docker-php-ext-install bcmath pdo_pgsql

COPY ./zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./zzz-php.ini /usr/local/etc/php/conf.d/zzz-php.ini

WORKDIR /workspace
