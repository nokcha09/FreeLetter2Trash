FROM php:8.2-fpm-alpine

# bash 설치 (필요한 경우)
RUN apk add --no-cache bash

RUN apk add --no-cache \
    mysql-client \
    git \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install opcache \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

# php-fpm 설정 파일 복사
COPY ./php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf

# composer.json 파일 복사
COPY composer.json ./

# Composer 의존성 설치
RUN composer install --no-dev --no-interaction --optimize-autoloader

# 나머지 프로젝트 파일 복사
COPY . .

WORKDIR /var/www/html