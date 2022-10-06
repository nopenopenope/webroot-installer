FROM php:8.1-cli-buster

ENV PHP_IDE_CONFIG=serverName=webroot

RUN apt update && \
    apt install --no-install-recommends --assume-yes --quiet git \
  software-properties-common \
  tar \
  vim \
  zip \
  unzip \
  apt-transport-https \
  ca-certificates \
  curl \
  zlib1g-dev \
  libzip-dev

RUN docker-php-ext-install zip
RUN pecl channel-update pecl.php.net
RUN pecl install xdebug
ADD ./docker/build/php/etc/20-xdebug.ini /usr/local/etc/php/conf.d/20-xdebug.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
WORKDIR /var/www/html

CMD tail -F /var/www/html/var/*
