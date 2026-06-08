FROM php:8.1-cli

RUN apt-get update && apt-get install -y git unzip curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction

CMD php artisan serve --host=0.0.0.0 --port=8000
