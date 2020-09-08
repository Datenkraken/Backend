ARG INSTALL_CRON=1

# Builder (Fat image with all build related tools)
FROM thecodingmachine/php:7.3-v3-apache-node10 AS builder

COPY --chown=docker:docker . .

ENV PHP_EXTENSION_MONGODB=1

RUN composer install --quiet --optimize-autoloader --no-interaction --ignore-platform-reqs && \
    npm set progress=false && \
    npm config set depth 0 && \
    npm install && \
    npm run prod && \
    rm -rf node_modules

# Production image
FROM thecodingmachine/php:7.3-v3-apache

ENV TEMPLATE_PHP_INI=production \
    PHP_EXTENSION_MONGODB=1 \
    APACHE_EXTENSION_HEADERS=1 \
    APACHE_DOCUMENT_ROOT=webapp/public/ \
    APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    TZ=Europe/Berlin \
    CRON_USER=root \
    CRON_SCHEDULE="* * * * *" \
    CRON_COMMAND="php /var/www/html/webapp/artisan queue:work --queue=high,standard,low --sleep=3 --tries=3 --stop-when-empty --once && php /var/www/html/webapp/artisan schedule:run" \
    STARTUP_COMMAND_1="sudo chown -R www-data:www-data /var/www/html/webapp/storage && sudo chmod -R 755 /var/www/html/webapp/storage && sudo php artisan migrate --force"

COPY --from=builder --chown=docker:docker /var/www/html /var/www/html/webapp

WORKDIR /var/www/html/webapp

USER root

RUN apt-get -y update && \
    apt-get -y upgrade  && \
    mkdir -p /var/www/html/env && \
    chown docker:docker /var/www/html/env && \
    cp /var/www/html/webapp/.env.docker /var/www/html/env/.env && \
    ln -s /var/www/html/env/.env /var/www/html/webapp/.env && \
    chown -R www-data:www-data /var/www/html/webapp/storage && \
    chmod -R 755 /var/www/html/webapp/storage

USER docker
