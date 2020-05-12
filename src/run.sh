#!/bin/sh

cd /app || exit

php artisan swoole:http start

