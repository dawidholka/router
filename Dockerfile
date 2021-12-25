FROM php:8.0-cli-alpine3.15

# Install packages and remove default server definition
RUN apk --no-cache add socat libzip-dev libpng-dev \
    curl tzdata htop mysql-client dcron net-tools && \
    docker-php-ext-install exif pcntl zip gd mysqli pdo pdo_mysql bcmath ctype pdo_mysql pcntl sockets && \
    ln -s /usr/bin/php8 /usr/bin/php

# Install PHP tools
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
# Configure PHP-FPM
COPY docker/php.ini /etc/php8/conf.d/custom.ini

# Setup document root
RUN mkdir -p /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run

RUN ln -sf /proc/1/fd/1 /var/log/octane.log

COPY --from=spiralscout/roadrunner:2.6.6 /usr/bin/rr /usr/bin/rr

COPY docker/php.ini "$PHP_INI_DIR/php.ini"

# Switch to use a non-root user from here on
USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody . /var/www/html/

# Optimization
RUN composer install --optimize-autoloader --no-dev --no-interaction
# Install octane
RUN php artisan octane:install --server=roadrunner

# Expose the port nginx is reachable on
EXPOSE 8080

#RUN #php artisan route:cache

# Start octane
CMD php artisan octane:start --port=8080 --host="0.0.0.0"

# https://github.com/laravel/octane/issues/403#issuecomment-943401361
HEALTHCHECK CMD kill -0 `cat /var/www/html/storage/logs/octane-server-state.json | awk -F"[,:}]" '{for(i=1;i<=NF;i++){if($i~/'masterProcessId'\042/){print $(i+1)}}}' | tr -d '"' | sed -n 1p`
