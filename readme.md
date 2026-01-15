# API Документация - Поиск товаров

## Endpoint: GET /api/products

Возвращает список товаров с возможностью фильтрации, сортировки и пагинации.

## Подготовка к тестированию

### Запуск приложения

```bash
# Клонировать репозиторий
git clone https://github.com/fningcom/vovo.git

# Собрать образ Docker
docker-compose build

# Запустить контейнеры
docker-compose up -d

# Перейти в директорию проекта
cd vovo

# Создать файл окружения
cp .env.example .env

# Сгенерировать ключ приложения
php artisan key:generate

# Настроить параметры базы данных в .env файле
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel
DB_PASSWORD=password

# Установить зависимости
docker-compose run composer install

# Запустить миграции
docker-compose run artisan migrate

# Заполнить базу тестовыми данными
docker-compose run artisan db:seed

```

### URL
```
GET /api/products
```

### Параметры запроса

| Параметр | Тип | Обязательный | Описание |
|----------|-----|--------------|----------|
| `q` | string | Нет | Поиск по подстроке в названии товара |
| `price_from` | numeric | Нет | Минимальная цена товара |
| `price_to` | numeric | Нет | Максимальная цена товара |
| `category_id` | integer | Нет | ID категории для фильтрации |
| `in_stock` | boolean | Нет | Наличие товара на складе (true/false) |
| `rating_from` | numeric | Нет | Минимальный рейтинг товара (0-5) |
| `sort` | string | Нет | Сортировка: `price_asc`, `price_desc`, `rating_desc`, `newest` |
| `per_page` | integer | Нет | Количество товаров на странице (1-100, по умолчанию 15) |
| `page` | integer | Нет | Номер страницы для пагинации |

### Примеры запросов

#### 1. Получить все товары (с пагинацией по умолчанию)
```bash
GET /api/products
```

#### 2. Поиск товаров по названию
```bash
GET /api/products?q=телефон
```

#### 3. Фильтрация по цене
```bash
GET /api/products?price_from=1000&price_to=5000
```

#### 4. Фильтрация по категории
```bash
GET /api/products?category_id=1
```

#### 5. Только товары в наличии
```bash
GET /api/products?in_stock=true
```

#### 6. Товары с рейтингом от 4.0
```bash
GET /api/products?rating_from=4.0
```

#### 7. Сортировка по цене (по возрастанию)
```bash
GET /api/products?sort=price_asc
```

#### 8. Сортировка по рейтингу (по убыванию)
```bash
GET /api/products?sort=rating_desc
```

#### 9. Новые товары
```bash
GET /api/products?sort=newest
```

#### 10. Комбинированный запрос
```bash
GET /api/products?q=ноутбук&price_from=30000&price_to=100000&category_id=1&in_stock=true&rating_from=4.0&sort=price_asc&per_page=20
```

### Формат ответа

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "name": "Название товара",
      "price": "1999.99",
      "category_id": 1,
      "in_stock": true,
      "rating": 4.5,
      "created_at": "2026-01-15T19:00:00.000000Z",
      "updated_at": "2026-01-15T19:00:00.000000Z",
      "category": {
        "id": 1,
        "name": "Электроника",
        "created_at": "2026-01-15T19:00:00.000000Z",
        "updated_at": "2026-01-15T19:00:00.000000Z"
      }
    }
  ],
  "first_page_url": "http://localhost/api/products?page=1",
  "from": 1,
  "last_page": 10,
  "last_page_url": "http://localhost/api/products?page=10",
  "links": [
    {
      "url": null,
      "label": "&laquo; Previous",
      "active": false
    },
    {
      "url": "http://localhost/api/products?page=1",
      "label": "1",
      "active": true
    },
    {
      "url": "http://localhost/api/products?page=2",
      "label": "2",
      "active": false
    }
  ],
  "next_page_url": "http://localhost/api/products?page=2",
  "path": "http://localhost/api/products",
  "per_page": 15,
  "prev_page_url": null,
  "to": 15,
  "total": 100
}
```

### Коды ответов

| Код | Описание |
|-----|----------|
| 200 | Успешный запрос |
| 422 | Ошибка валидации параметров |
| 500 | Внутренняя ошибка сервера |

### Ошибки валидации

При неверных параметрах API вернет ответ с кодом 422:

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "price_from": [
      "The price from must be a number."
    ],
    "category_id": [
      "The selected category id is invalid."
    ]
  }
}
```

## Установка и настройка

### 1. Выполнить миграции
```bash
php artisan migrate
```

### 2. Заполнить базу тестовыми данными
```bash
php artisan db:seed
```

Это создаст:
- 10 категорий
- 100 товаров (по 10 товаров в каждой категории)

### 3. Запустить сервер
```bash
php artisan serve
```

API будет доступен по адресу: `http://localhost:8000/api/products`

## Структура базы данных

### Таблица `categories`
- `id` - первичный ключ
- `name` - название категории
- `created_at` - дата создания
- `updated_at` - дата обновления

### Таблица `products`
- `id` - первичный ключ
- `name` - название товара (с индексом для быстрого поиска)
- `price` - цена товара (decimal 10,2)
- `category_id` - внешний ключ на таблицу categories
- `in_stock` - наличие на складе (boolean)
- `rating` - рейтинг товара (float 0-5)
- `created_at` - дата создания
- `updated_at` - дата обновления

## Производительность

- Для поля `name` создан индекс для оптимизации поиска по LIKE
- Используются Eloquent scopes для чистого и переиспользуемого кода
- Eager loading для категорий (`with('category')`) для избежания N+1 проблемы
- Пагинация для ограничения количества возвращаемых записей
