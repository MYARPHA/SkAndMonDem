# SkAndMonDem - Feedback App

Тестовое задание для Скарлет и Монарх

Простое веб-приложение для сбора отзывов пользователей, написанное на PHP с использованием паттерна MVC и Docker.

## 📋 Описание

Приложение позволяет:
- Просматривать список отзывов
- Добавлять новые отзывы
- Хранить данные в MySQL базе данных
- Работать в Docker контейнерах

## 🔧 Требования

- Docker
- Docker Compose
- PHP 7.4+
- MySQL 5.7+

## 🚀 Установка и запуск

### 1. Клонирование репозитория
```bash
git clone https://github.com/MYARPHA/SkAndMonDem.git
cd SkAndMonDem
```

### 2. Запуск с Docker
```bash
docker-compose up -d
```

### 3. Миграция базы данных
```bash
docker-compose exec app php migrate.php
```

### 4. Открыть приложение
```
http://localhost:8000
```

## 📁 Структура проекта

```
feedback-app/
├── app/
│   ├── Controllers/          # Контроллеры
│   │   └── FeedbackController.php
│   ├── Core/                 # Ядро приложения
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Migration.php
│   │   ├── Model.php
│   │   └── Router.php
│   ├── Models/               # Модели данных
│   │   └── Feedback.php
│   └── Views/                # Представления (Views)
│       └── feedback/
│           └── index.php
├── config/
│   └── database.php          # Конфигурация БД
├── public/
│   ├── css/
│   │   └── style.css
│   └── js/
│       └── app.js
├── sql/
│   └── schema.sql            # SQL схема
├── docker-compose.yml
├── Dockerfile
├── migrate.php               # Миграции
└── index.php                 # Входная точка
```

## 🗄️ База данных

Таблица `feedback` содержит:
- `id` - уникальный идентификатор
- `name` - имя автора
- `email` - email автора
- `message` - текст отзыва
- `created_at` - дата создания

## 💻 API маршруты

- `GET /` - просмотр всех отзывов
- `POST /add` - добавление нового отзыва
- `GET /feedback/:id` - просмотр одного отзыва

## ⚙️ Конфигурация

Отредактируйте `config/database.php` для изменения параметров подключения:

```php
define('DB_HOST', 'mysql');
define('DB_NAME', 'feedback_db');
define('DB_USER', 'root');
define('DB_PASS', 'password');
```

## 🛑 Остановка приложения

```bash
docker-compose down
```

## 📝 Лицензия

MIT
