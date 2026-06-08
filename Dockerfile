FROM php:8.2-cli

RUN apt-get update && apt-get install -y git unzip curl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction

RUN php artisan key:generate --force

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
