FROM php:8.2-cli

WORKDIR /app

COPY . /app

RUN apt-get update && apt-get install -y \
    unzip curl git libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN php artisan optimize:clear || true
RUN php artisan migrate --force || true

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000