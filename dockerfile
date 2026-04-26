FROM php:8.2-cli

WORKDIR /app

COPY . /app

RUN apt-get update && apt-get install -y \
    unzip curl git

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN composer install --no-dev --optimize-autoloader

# 🔥 IMPORTANT : créer les tables sur Render DB
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000