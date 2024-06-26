FROM php:8.3.4-fpm

RUN usermod  -u 1000 www-data
RUN groupmod -g 1000 www-data

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions xdebug opcache pdo_pgsql @composer
