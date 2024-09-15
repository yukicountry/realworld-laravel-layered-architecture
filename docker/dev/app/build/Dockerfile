FROM php:8.2-fpm

WORKDIR /tmp

RUN apt update && \
    apt install -y libpq-dev zip unzip && \
    # install php extensions
    docker-php-ext-install bcmath pdo_pgsql && \
    # install composer
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv ./composer.phar /usr/local/bin/composer

COPY ./zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./zzz-php.ini /usr/local/etc/php/conf.d/zzz-php.ini

WORKDIR /workspace
