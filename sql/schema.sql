CREATE DATABASE IF NOT EXISTS feedback_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Переключаемся на созданную БД
USE feedback_app;

-- Таблица сообщений обратной связи
CREATE TABLE IF NOT EXISTS feedbacks (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(255) NOT NULL,
    phone      VARCHAR(20)  NOT NULL,
    email      VARCHAR(255) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
