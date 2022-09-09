FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
        openssl \
        git \
        zlib1g \
        zlib1g-dev \
        libfreetype6-dev \
        libssl-dev \
        libjpeg62-turbo-dev \
        libmagickwand-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        zip \
        unzip \
        ffmpeg \
        librabbitmq-dev;

RUN docker-php-ext-install pdo_mysql gd intl zip sockets

RUN pecl install amqp && docker-php-ext-enable amqp

# Установка composer'a
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
