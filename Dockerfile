FROM php:8.2-cli

RUN apt-get update && apt-get install -y git unzip curl nginx

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction

RUN php artisan key:generate --force

RUN php artisan config:cache

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80

CMD php artisan serve --host=0.0.0.0 --port=80
