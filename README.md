# Простое приложение по REST API (тестовое задание)

## О проекте
Данное API управляет взаимодействиями с сущностями "Товары" (Goods) и "Категории" (Categories), которые связаны отношением "многие-ко-многим".

## Технологический стек
* PHP 8.2
* SMS Aero API
* SMS.RU Client
* PHPUnit
* MySQL

## Установка

```shell
composer install
```

* Требуется развернуть локальный сервер и базу данных, например, на Open Server, XAMPP и т.д.<br>
* Создать БД и таблицы по файлам в папке migrations.
* Добавить свои данные в файл .env.

## Реализованный функционал

* Взаимодействие c товарами
  * GET /goods?search=value
  * POST /goods/{id}
  * PUT /goods/{id}
  * DELETE /goods/{id}

* Взаимодействие c категориями
  * GET /categories?search=value
  * POST /categories/{id}
  * PUT /categories/{id}
  * DELETE /categories/{id}

* Интеграция с сервисами SMS-рассылки
  * SMS Aero
  * SMS.RU

* Тестирование CRUD операций

## Примеры запросов и ответов

<div align="center">
  <img src="https://github.com/flametong/nk-test-api/assets/32167273/b6449990-5d24-48b1-ba10-ed5e5e0d1016" alt="Get goods">
</div>
<div align="center">
  <img src="https://github.com/flametong/nk-test-api/assets/32167273/78b83e2a-e09a-4a16-9123-9cdaa970fc25" alt="Post goods">
</div>
