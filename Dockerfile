FROM jgcl88/alpine-nginx-php:oracle

COPY ./app /app

RUN chmod +x /app/docker/start.sh && composer install && chown -R www:www /app

EXPOSE 80

CMD ["/app/docker/start.sh"]