<?php

/**
 * Конфигурация подключения к базе данных.
 * Все параметры могут быть переопределены через переменные окружения
 * (полезно для Docker-развёртывания).
 */

declare(strict_types=1);

return [
    'driver'   => 'mysql',                              // Драйвер БД (mysql, pgsql, mariadb)
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',    // Хост (из env или localhost)
    'port'     => (int)(getenv('DB_PORT') ?: 3306),    // Порт (из env или 3306)
    'dbname'   => getenv('DB_NAME') ?: 'feedback_app',  // Имя БД
    'charset'  => 'utf8mb4',                            // Кодировка (полная поддержка Юникода)
    'username' => getenv('DB_USER') ?: 'root',          // Пользователь
    'password' => getenv('DB_PASS') ?: '',              // Пароль
];
