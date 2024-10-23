FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libssl-dev \
    git \
    unzip \
    zip \
    libicu-dev \
    libonig-dev \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/

RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]
