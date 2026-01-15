## Установка Laravel 

**Команда composer:** 

`:> docker-compose run composer create-project laravel/laravel .`

**Настроить ./src/.env**

DB_CONNECTION=mysql \
DB_HOST=mysql \
DB_PORT=3306 \
DB_DATABASE=laravel_db \
DB_USERNAME=laravel \
DB_PASSWORD=password


**Запуск команд artisan:**

`:> docker-compose run artisan migrate`

**Остановить и удалить все контейнеры:**

`:> docker-compose down -v`
