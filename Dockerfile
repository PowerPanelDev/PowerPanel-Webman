FROM composer AS composer

COPY . /data

WORKDIR /data

RUN composer config -g repos.packagist composer https://mirrors.tencent.com/composer/ && \
    composer install

FROM php:8.1-cli

WORKDIR /data

ENV TZ=Asia/Shanghai

RUN docker-php-ext-install pcntl pdo_mysql

COPY --from=composer /data .

CMD ["php", "webman", "start"]