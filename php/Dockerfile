FROM php:7.4-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
MAINTAINER Mohammed Halhnini <mohamedhalhnini@gmail.com>

# Parametrage du proxy
#ENV HTTP_PROXY='http://193.56.47.8:8080' \
#    HTTPS_PROXY='http://193.56.47.8:8080' \
#    NO_PROXY='localhost,127.0.0.1' \
#    http_proxy='http://193.56.47.8:8080' \
#    https_proxy='http://193.56.47.8:8080' \
#    no_proxy='localhost,127.0.0.1'

RUN apt-get update \
    && apt-get install -y --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev librabbitmq-dev libssh-dev;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-configure intl
RUN pecl install amqp
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl
RUN docker-php-ext-enable amqp


WORKDIR /var/www/

