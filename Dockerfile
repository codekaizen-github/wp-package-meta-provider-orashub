ARG PHP_VERSION=8.1
FROM php:${PHP_VERSION}-cli AS dependencies

USER root

WORKDIR /workspace

RUN apt-get update && apt-get install -y curl unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM dependencies AS test

COPY ./ ./

FROM dependencies AS dev

RUN apt-get update && apt-get install -y \
    git \
    vim \
    less \
    sudo \
    && rm -rf /var/lib/apt/lists/*

