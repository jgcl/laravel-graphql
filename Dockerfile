FROM php:7.4.5-cli



#RUN apk add --no-cache ${PHPIZE_DEPS} \
    #&& docker-php-ext-install opcache \
    #&& pecl install swoole
    #&& docker-php-ext-enable swoole \
    #&& apk del ${PHPIZE_DEPS}

#RUN echo 'extension=swoole.so' > /usr/local/etc/php/conf.d/docker-php-ext-swoole.ini

COPY ./src /app

#RUN chmod +x ./composer.phar && ./composer.phar install --no-dev --no-suggest \
    #&& echo 'APP_KEY=base64:+F7E108ptxPVoIjIz2a2+kgQcHapBeGiG2fcXGJ4W4A=' > .env \
    #&& php artisan optimize

WORKDIR /app

CMD "php /app/artisan serve"

