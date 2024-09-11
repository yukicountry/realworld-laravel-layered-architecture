FROM php:8.3-fpm

SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

RUN apt update && \
    apt install -y libpq-dev && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    echo "Asia/Tokyo" > /etc/timezone && \
    docker-php-ext-install bcmath pdo_pgsql

COPY ./zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./zzz-php.ini /usr/local/etc/php/conf.d/zzz-php.ini

WORKDIR /workdir
