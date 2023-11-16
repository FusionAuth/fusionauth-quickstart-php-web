# FROM alpine:3.18.4

# WORKDIR /var/www/html

# RUN apk add composer=2.6.5-r0 nginx=1.24.0-r7 php82=8.2.12-r0 php82-fpm=8.2.12-r0


FROM php:8.2-apache

# Update Apache configuration to point to the public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

WORKDIR /var/www/html
