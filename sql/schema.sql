CREATE DATABASE IF NOT EXISTS feedback_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Переключаемся на созданную БД
USE feedback_app;

-- Таблица сообщений обратной связи
CREATE TABLE IF NOT EXISTS feedbacks (
    id         INT AUTO_INCREMENT PRIMARY KEY,  -- Уникальный идентификатор
    full_name  VARCHAR(255) NOT NULL,            -- ФИО отправителя
    email      VARCHAR(255) NOT NULL,            -- Email отправителя
    message    TEXT         NOT NULL,            -- Текст сообщения
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP  -- Дата и время отправки
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
