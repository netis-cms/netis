# image from docker hub
FROM php:8.3-apache
MAINTAINER Zdeněk Papučík <zdenek.papucik@gmail.com>

# build-time customization
ARG DEBIAN_FRONTEND=noninteractive

# run commands
RUN apt update && apt upgrade -y && a2enmod rewrite
# RUN a2enmod ssl

# php extensions
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# php configuration
COPY conf/php.ini /usr/local/etc/php/conf.d
COPY conf/000-default.conf /etc/apache2/sites-available/

# the ports
EXPOSE 80
