FROM php:8.1-cli

RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    libpq-dev \
    libpng-dev \
    libonig-dev

RUN docker-php-ext-install pdo_mysql mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --no-dev

RUN php artisan key:generate --force

RUN php artisan config:cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
