<?php

/*
 * Точка входа (Front Controller).
 * Все HTTP-запросы проходят через этот файл.
 * Используется автозагрузка классов и маршрутизация.
 */

declare(strict_types=1);

// PSR-4 автозагрузка для пространства имён App\
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    // Проверяем, относится ли класс к нашему проекту
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    // Преобразуем имя класса в путь к файлу
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;
use App\Controllers\FeedbackController;

// Создаём роутер и регистрируем маршруты
$router = new Router();

$router->get('/', [FeedbackController::class, 'index']);       // Главная страница с формой и сообщениями
$router->get('/list', [FeedbackController::class, 'list']);    // AJAX — получение списка сообщений (JSON)
$router->post('/submit', [FeedbackController::class, 'submit']); // AJAX — отправка формы (JSON)

// Запускаем обработку запроса
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
