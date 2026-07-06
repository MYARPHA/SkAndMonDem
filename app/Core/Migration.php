<?php

/**
 * Скрипт миграции БД.
 * Создаёт базу данных и таблицу feedbacks,
 * если они ещё не существуют.
 */

declare(strict_types=1);

namespace App\Core;

use PDO;

class Migration
{
    private PDO $pdo;

    /**
     * Подключается к MySQL без указания конкретной БД,
     * чтобы иметь возможность создать её.
     */
    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        // DSN без dbname — подключаемся к серверу, а не к БД
        $dsn = sprintf(
            '%s:host=%s;port=%d;charset=%s',
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['charset']
        );

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    /**
     * Выполняет миграцию: создаёт БД и таблицу.
     */
    public function run(): void
    {
        // Создаём БД, если её нет (с utf8mb4 для полной поддержки Юникода)
        $this->pdo->exec(
            "CREATE DATABASE IF NOT EXISTS `{$this->pdo->quote('feedback_app')}` 
             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
        );
        $this->pdo->exec('USE `feedback_app`');

        // Создаём таблицу feedbacks
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS feedbacks (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                full_name  VARCHAR(255) NOT NULL,
                email      VARCHAR(255) NOT NULL,
                message    TEXT         NOT NULL,
                created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );

        echo "Migration completed successfully.\n";
    }
}
