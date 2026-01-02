ARG PHP_VERSION=8.4
FROM php:${PHP_VERSION}-cli AS dependencies

USER root

RUN apt-get update && apt-get install -y curl unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

FROM dependencies AS test

FROM dependencies AS dev

RUN apt-get update && apt-get install -y \
    git \
    vim \
    less \
    sudo \
    && rm -rf /var/lib/apt/lists/*

