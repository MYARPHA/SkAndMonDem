<?php

/**
 * Базовый контроллер.
 * Содержит вспомогательные методы для рендеринга вьюх и JSON-ответов.
 */

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Рендерит HTML-шаблон с переданными данными.
     * Переменные из массива $data извлекаются в локальную область видимости.
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        require __DIR__ . '/../Views/' . $view . '.php';
    }

    /**
     * Возвращает JSON-ответ с заданным HTTP-статусом.
     * Используется для AJAX-эндпоинтов.
     */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
