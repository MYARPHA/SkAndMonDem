<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Feedback extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM feedbacks ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function save(string $fullName, string $email, string $message): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO feedbacks (full_name, email, message) VALUES (:full_name, :email, :message)'
        );

        return $stmt->execute([
            ':full_name' => $fullName,
            ':email'     => $email,
            ':message'   => $message,
        ]);
    }
}
