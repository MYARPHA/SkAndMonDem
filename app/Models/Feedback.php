<?php

/**
 * Модель Feedback
 * Отвечает за работу с таблицей feedbacks в БД
 * Все запросы выполняются через PDO с prepared statements
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Feedback extends Model
{
    /**
     * Возвращает все сообщения, отсортированные по дате (сначала новые)
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM feedbacks ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Сохраняет новое сообщение в БД
     * Использует prepared statements — защита от SQL-инъекций
     */
    public function save(string $fullName, string $phone, string $email, string $message): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO feedbacks (full_name, phone, email, message) VALUES (:full_name, :phone, :email, :message)'
        );

        return $stmt->execute([
            ':full_name' => $fullName,
            ':phone'     => $phone,
            ':email'     => $email,
            ':message'   => $message,
        ]);
    }
}
