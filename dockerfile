FROM php:8.2-cli

WORKDIR /app

# Copier le projet
COPY . /app

# Installer paquets système + PostgreSQL driver
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Installer dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Permissions Laravel
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

# Démarrage Render : migration puis lancement site
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000