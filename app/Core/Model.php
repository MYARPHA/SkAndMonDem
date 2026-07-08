<?php

/**
 * Базовая модель
 * Предоставляет наследникам PDO-соединение с БД
 */

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    // Экземпляр PDO, доступный во всех моделях-наследниках
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
