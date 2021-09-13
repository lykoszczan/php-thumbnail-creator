FROM php:7.4-fpm

RUN apt-get update -y && apt-get install -y libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev libfreetype6-dev

RUN apt-get update && apt-get install -y zlib1g-dev 

RUN docker-php-ext-configure gd --enable-gd --with-webp --with-jpeg
RUN docker-php-ext-install gd