FROM php:8.2-cli

RUN apt-get update && apt-get install -y unzip curl git

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --no-scripts

RUN php artisan key:generate --force

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
