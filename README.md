# Запуск

Для запуска вам необходим [Docker](https://www.docker.com/products/docker-desktop/)

после необходимо запустить следующие команды:
```
docker-compose up --build -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```