<?php

declare(strict_types=1);

return [
    'driver'   => 'mysql',
    'host'     => getenv('DB_HOST') ?: '127.0.0.1',
    'port'     => (int)(getenv('DB_PORT') ?: 3306),
    'dbname'   => getenv('DB_NAME') ?: 'feedback_app',
    'charset'  => 'utf8mb4',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
];
