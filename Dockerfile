FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

RUN addgroup -g 1000 -S app && \
    adduser -u 1000 -S app -G app

RUN apk add \
    autoconf \
    build-base \
    git

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY . /var/www/html

WORKDIR /var/www/html/public

EXPOSE 9000

CMD ["php-fpm"]