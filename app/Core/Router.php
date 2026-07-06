<?php

/**
 * Простой маршрутизатор (Router).
 * Сопоставляет HTTP-метод и URI с методом контроллера.
 */

declare(strict_types=1);

namespace App\Core;

class Router
{
    // Список зарегистрированных маршрутов: [HTTP_METHOD][URI] => [ControllerClass, action]
    private array $routes = [];

    /**
     * Регистрирует GET-маршрут.
     */
    public function get(string $uri, array $handler): void
    {
        $this->routes['GET'][$uri] = $handler;
    }

    /**
     * Регистрирует POST-маршрут.
     */
    public function post(string $uri, array $handler): void
    {
        $this->routes['POST'][$uri] = $handler;
    }

    /**
     * Обрабатывает входящий запрос: ищет маршрут и вызывает обработчик.
     */
    public function dispatch(string $method, string $uri): void
    {
        // Извлекаем путь без query-параметров
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';

        // Если маршрут найден — создаём контроллер и вызываем метод
        if (isset($this->routes[$method][$uri])) {
            [$controllerClass, $action] = $this->routes[$method][$uri];
            $controller = new $controllerClass();
            $controller->$action();
            return;
        }

        // 404, если маршрут не найден
        http_response_code(404);
        echo '404 Not Found';
    }
}
