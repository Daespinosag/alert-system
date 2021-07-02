# Set the base image.
FROM node:lts-alpine as build-stage 

# Create and define the node_modules's cache directory.
RUN mkdir /usr/src && mkdir /usr/src/cache
WORKDIR /usr/src/cache

# Install the application's dependencies into the node_modules's cache directory.
COPY package*.json ./
RUN npm install

# Create and define the application's working directory.
RUN mkdir /usr/src/app 
WORKDIR /usr/src/app
ADD ./entrypoint.sh /docker-entrypoint-initfront.d/
RUN dos2unix /docker-entrypoint-initfront.d/entrypoint.sh

FROM php:7.2-apache as web
#dependencies and utilities
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    iputils-ping \
    git \
    libpq-dev \
    npm \
    curl
#composer installation and laravel extension
RUN apt-get update --yes \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && apt-get install --yes libbz2-dev \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && apt-get install --yes wget \
    && HASH="$(wget -q -O - https://composer.github.io/installer.sig)" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && docker-php-ext-install -j$(nproc) \
        bz2 \
        bcmath \
        zip \
        gd \
        pdo_mysql \
        mysqli \
        shmop \
        sockets \
        pdo_pgsql \
        pgsql \
    && a2enmod rewrite            
COPY ./memory-limit.ini /usr/local/etc/php/conf.d    
COPY ./timezone.ini /usr/local/etc/php/conf.d
