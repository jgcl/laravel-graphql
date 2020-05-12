FROM php:7.4.5-cli-alpine

RUN docker-php-ext-install bcmath mysqli pdo_mysql

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
	&& pecl install redis xdebug \
	&& docker-php-ext-enable redis \
	&& docker-php-ext-enable xdebug \
    && apk del -f .build-deps

WORKDIR /app

COPY ./src /app

RUN chmod +x ./composer.phar \
    && ./composer.phar install --no-suggest

RUN ./vendor/bin/phpunit --coverage-text

RUN echo 'APP_ENV=local' > .env \
    && php artisan optimize \
    && php artisan config:clear

EXPOSE 80

ENTRYPOINT ["sh", "-c"]

CMD ["php artisan serve --host=0.0.0.0 --port=80"]
