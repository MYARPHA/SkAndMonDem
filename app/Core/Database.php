<?php

/**
 * Пул подключений к БД через PDO
 */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Возвращает единственный экземпляр PDO-соединения
     * Настройки подключения читаются из config/database.php!
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';

            //  DSN строка для подключения
            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            try {
                // Подключаемся с настройками безопасности:
                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // При ошибке подключения возвращаем 500
                http_response_code(500);
                echo 'Database connection failed: ' . $e->getMessage();
                exit;
            }
        }

        return self::$instance;
    }
}
