<<<<<<< HEAD
FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    unzip \
    git \
    libpq-dev \
    libpng-dev \
    libonig-dev

RUN docker-php-ext-install pdo_mysql mbstring gd
=======
FROM php:8.2-cli

RUN apt-get update && apt-get install -y unzip curl git
>>>>>>> 3b1ddcc77d4416edbd56f8abd7d87d366d7a63bb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-interaction --no-scripts

RUN php artisan key:generate --force

EXPOSE 8000

<<<<<<< HEAD
EXPOSE 80

CMD php artisan serve --host=0.0.0.0 --port=80
=======
CMD php artisan serve --host=0.0.0.0 --port=8000
>>>>>>> 3b1ddcc77d4416edbd56f8abd7d87d366d7a63bb
