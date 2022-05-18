# image from docker hub
FROM php:8.0-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# build-time customization
ARG DEBIAN_FRONTEND=noninteractive

# run commands
RUN apt update && apt upgrade -y && a2enmod ssl && a2enmod rewrite

# php extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# php configuration
COPY docker/conf/php.ini/ /etc/php/8.0/apache2/php.ini/
COPY docker/conf/000-default.conf/ /etc/apache2/sites-available/

# the ports
EXPOSE 80
