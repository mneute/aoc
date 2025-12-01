FROM php:8.5-cli

WORKDIR /srv/app

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./php/docker-php-custom.ini "$PHP_INI_DIR/conf.d/"

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions ds
RUN apt update; \
    apt install git zip -y; \
    git config --global --add safe.directory /srv/app;

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY . /srv/app/

RUN composer install
