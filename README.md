<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API конфигурации автомобилей

API для управления автомобилями, их комплектациями, опциями и ценами.

## Функциональность

- CRUD-операции для автомобилей, опций, комплектаций и цен
- Публичный API-метод для получения доступных автомобилей с комплектациями и актуальными ценами
- Кеширование данных с использованием Redis
- Документация API через Swagger

## Технологии

- Laravel 12.0
- PostgreSQL
- Redis
- Swagger (L5 Swagger)

## Обьяснение архитектурных решений
- Не делаю repository layer из за того, что laravel и так предоставляет широкий функционал моделей и нам это не требуется
- Сделал service классы с бизнес логикой

## Установка и запуск с использованием Laravel Sail

1. Клонировать репозиторий:
```bash
git clone https://github.com/yourusername/car-configuration-api.git
cd car-configuration-api
```

2. Настроить окружение:
```bash
cp .env.example .env
```

3. Установить зависимости Composer:
```bash
composer install
```

4. Установить Laravel Sail с PostgreSQL и Redis:
```bash
php artisan sail:install
```
При запросе выберите следующие сервисы:
- PostgreSQL
- Redis

5. Запустить контейнеры:
```bash
./vendor/bin/sail up -d
```

6. Настройка приложения:
```bash
# Генерация ключа приложения
./vendor/bin/sail artisan key:generate

# Запуск миграций
./vendor/bin/sail artisan migrate

# Заполнение базы данных тестовыми данными
./vendor/bin/sail artisan db:seed

# Генерация документации Swagger
./vendor/bin/sail artisan l5-swagger:generate
```

7. Доступ к приложению:
```
http://localhost
```

## Установка и запуск без Sail

1. Клонировать репозиторий:
```bash
git clone https://github.com/yourusername/car-configuration-api.git
cd car-configuration-api
```

2. Установить зависимости:
```bash
composer install
```

3. Настроить окружение:
```bash
cp .env.example .env
# Отредактируйте .env файл для настройки базы данных, Redis и параметров приложения
```

4. Сгенерировать ключ приложения:
```bash
php artisan key:generate
```

5. Запустить миграции:
```bash
php artisan migrate
```

6. Заполнить базу данных тестовыми данными:
```bash
php artisan db:seed
```

7. Сгенерировать документацию Swagger:
```bash
php artisan l5-swagger:generate
```

8. Запустить сервер:
```bash
php artisan serve
```

## Документация API

После установки вы можете получить доступ к документации API по адресу:
```
http://localhost/api/documentation
```

## API Endpoints

### Публичные эндпоинты

- `GET /api/cars/available` - Получить все доступные автомобили с их комплектациями и актуальными ценами

### CRUD эндпоинты

#### Автомобили (Cars)
- `GET /api/cars` - Получить все автомобили
- `GET /api/cars/{id}` - Получить информацию о конкретном автомобиле
- `POST /api/cars` - Создать новый автомобиль
- `PUT /api/cars/{id}` - Обновить автомобиль
- `DELETE /api/cars/{id}` - Удалить автомобиль

#### Опции (Options)
- `GET /api/options` - Получить все опции
- `GET /api/options/{id}` - Получить информацию о конкретной опции
- `POST /api/options` - Создать новую опцию
- `PUT /api/options/{id}` - Обновить опцию
- `DELETE /api/options/{id}` - Удалить опцию

#### Комплектации (Configurations)
- `GET /api/configurations` - Получить все комплектации
- `GET /api/configurations/{id}` - Получить информацию о конкретной комплектации
- `POST /api/configurations` - Создать новую комплектацию
- `PUT /api/configurations/{id}` - Обновить комплектацию
- `DELETE /api/configurations/{id}` - Удалить комплектацию

#### Цены (Prices)
- `GET /api/prices` - Получить все цены
- `GET /api/prices/{id}` - Получить информацию о конкретной цене
- `POST /api/prices` - Создать новую цену
- `PUT /api/prices/{id}` - Обновить цену
- `DELETE /api/prices/{id}` - Удалить цену

## Структура базы данных

- **Автомобили (Cars)**: id, name, created_at, updated_at
- **Опции (Options)**: id, name, created_at, updated_at
- **Комплектации (Configurations)**: id, car_id, name, created_at, updated_at
- **Комплектации-Опции (ConfigurationOption)**: id, configuration_id, option_id
- **Цены (Prices)**: id, configuration_id, price, start_date, end_date, created_at, updated_at
