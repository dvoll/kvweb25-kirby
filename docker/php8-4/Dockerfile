# MIT License

# Copyright (c) 2023 Fork Unstable Media GmbH

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.


FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

ARG TIME_ZONE=Europe/Berlin

RUN apk --no-cache add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data

ENV XDEBUG_MODE=off
ENV LANG=de_DE.UTF-8

# Install build dependencies
RUN apk add --update --no-cache \
  freetype-dev \
  libpng-dev \
  libwebp-dev \
  libjpeg-turbo-dev \
  linux-headers \
  tzdata \
  zlib-dev \
  libzip-dev \
  zip \
  icu-dev \
  icu-data-full \
  imagemagick \
  imagemagick-dev

RUN apk add --no-cache --virtual .build-dependencies \
  $PHPIZE_DEPS \
  && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install gd \
  && docker-php-ext-install zip \
  && docker-php-ext-install intl \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && pecl install imagick \
  && docker-php-ext-enable imagick \
  && apk del .build-dependencies

# Set timezone
RUN cp /usr/share/zoneinfo/$TIME_ZONE /etc/localtime && echo "$TIME_ZONE" > /etc/timezone

# Activate ERROR_REPORTING
RUN echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/error-reporting.ini

RUN echo "upload_max_filesize=50m" >> /usr/local/etc/php/conf.d/php.ini

# Activate Xdebug
RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
  && echo "xdebug.discover_client_host=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Add composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
