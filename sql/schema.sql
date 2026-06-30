CREATE DATABASE IF NOT EXISTS feedback_app
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE feedback_app;

CREATE TABLE IF NOT EXISTS feedbacks (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    full_name  VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    message    TEXT         NOT NULL,
    created_at TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
