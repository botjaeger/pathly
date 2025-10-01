FROM php:8.3-apache AS pathly_php

ENV WORK_DIR="/var/www" APP_ENV=prod
WORKDIR "$WORK_DIR"

RUN a2enmod rewrite

RUN apt update \
  && apt upgrade -y \
  && apt install -y --no-install-recommends \
    libzip-dev zip unzip \
    git wget netcat-traditional \
    default-mysql-client acl \
  && apt clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo mysqli pdo_mysql zip

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN composer global config --no-plugins allow-plugins.symfony/flex true
RUN composer global require "symfony/flex" --prefer-dist --no-progress --classmap-authoritative

COPY composer.json composer.lock symfony.lock "$WORK_DIR"/
RUN composer install --no-dev --no-progress --no-interaction --no-scripts && rm -rf /root/.composer

# Copy custom PHP configuration
COPY docker/php.ini /usr/local/etc/php/conf.d/php-custom.ini
COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY . "$WORK_DIR"

RUN composer dump-autoload --no-dev --classmap-authoritative
RUN composer auto-scripts --no-dev

COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

STOPSIGNAL SIGWINCH

EXPOSE 80
CMD ["apache2-foreground"]
