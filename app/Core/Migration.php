<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

class Migration
{
    private PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

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

    public function run(): void
    {
        $this->pdo->exec(
            "CREATE DATABASE IF NOT EXISTS `{$this->pdo->quote('feedback_app')}` 
             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
        );
        $this->pdo->exec('USE `feedback_app`');

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
