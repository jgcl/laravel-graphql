# Docker, PHP 7.4, Swoole, PHPCS, PHPStan, GraphQL and MySQL

falar que primeio fiz uma implementação rest
posteriormente fiz uma implementação graphql com o pacote xxx

falar que as urls são xxxx e xxxx
http://localhost:8080/graphiql
http://localhost:8080/graphql

./vendor/bin/phpstan analyse ./app --level=1
./vendor/bin/phpcs --standard=PSR12 ./app
./vendor/bin/phpcbf --standard=PSR12 ./app

php artisan swoole:http start

pecl install xdebug && docker-php-ext-enable xdebug

./vendor/bin/phpunit --coverage-html ./storage/logs/coverage
./vendor/bin/phpunit --coverage-text
