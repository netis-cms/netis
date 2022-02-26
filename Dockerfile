# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# build-time customization
ARG DEBIAN_FRONTEND=noninteractive

# run commands
RUN apt update && apt upgrade -y && a2enmod ssl && a2enmod rewrite
RUN apt install libfreetype6-dev libjpeg62-turbo-dev libpng-dev libicu-dev -y
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# php extensions
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install gd pdo pdo_mysql mysqli intl \
    && docker-php-ext-enable mysqli

# copy certificate to container
COPY docker/cert/* /etc/apache2/ssl/

# php configuration
COPY docker/conf/php.ini/ /etc/php/8.0/apache2/php.ini/
COPY docker/conf/000-default.conf/ /etc/apache2/sites-available/

# the ports
EXPOSE 80 443
