# Docker, PHP 7.4, PHPCS, PHPStan, GraphQL and MySQL

Simples projeto usando Docker com PHP 7.4, uma API com GraphQL e MySQL, PHPCS/PHPStan para lint e code coverage de 100%.  

Plugins:
- GraphQL: https://github.com/rebing/graphql-laravel
- PHP Unit: https://github.com/sebastianbergmann/phpunit

## Deploy com docker

```bash
docker-compose up -d
docker exec -it app php artisan migrate
```

## Deploy sem Docker

Requisitos:
- https://laravel.com/docs/7.x#server-requirements
- PDO SQlite
- XDebug 
- Criar um arquivo .env com as configurações do banco de dados

Rodar:
```bash
php artisan migrate
php artisan serve --host=127.0.0.1 --port=80
```

## GraphQL

- Editor: http://localhost:80/graphiql
- Endpoint: http://localhost:80/graphql

## Exemplos

- Depósito:
```bash
curl --location --request POST 'http://localhost:80/graphql' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
	"query": "mutation { depositar(conta:1, valor:50.00) { conta, saldo } }"
}'
```

- Saque:
```bash
curl --location --request POST 'http://localhost:80/graphql' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
	"query": "mutation { sacar(conta:1, valor:50.00) { conta, saldo } }"
}'
```

- Saldo:
```bash
curl --location --request POST 'http://localhost:80/graphql' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--data-raw '{
	"query": "query { saldo(conta:1) }"
}'
```

## Testes

```bash
./vendor/bin/phpstan analyse ./app --level=1
./vendor/bin/phpcs --standard=PSR12 ./app
./vendor/bin/phpcbf --standard=PSR12 ./app
./vendor/bin/phpunit --coverage-html ./public/codecoverage
./vendor/bin/phpunit --coverage-text
```
