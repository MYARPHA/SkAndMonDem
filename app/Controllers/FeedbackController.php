<?php

/**
 * Контроллер обратной связи.
 * Обрабатывает показ формы, отправку сообщений (AJAX)
 * и выдачу списка сообщений (JSON).
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    private Feedback $feedbackModel;

    /**
     * Инициализация модели для работы с БД
     */
    public function __construct()
    {
        $this->feedbackModel = new Feedback();
    }

    /**
     * GET / — главная страница
     * Рендерит форму и выводит список уже отправленных сообщений
     */
    public function index(): void
    {
        $messages = $this->feedbackModel->getAll();
        $this->render('feedback/index', ['messages' => $messages]);
    }

    /**
     * GET /list
     */
    public function list(): void
    {
        $messages = $this->feedbackModel->getAll();
        $this->json(['success' => true, 'messages' => $messages]);
    }

    /**
     * POST /submit
     * Принимает данные формы, валидирует и сохраняет в БД
     * Валидация происходит на стороне сервера (обязательные поля, формат email, длина)
     */
    public function submit(): void
    {
        // Извлекаем и обрезаем пробелы из полей формы
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $message  = trim($_POST['message'] ?? '');

        // Валидация полей
        $errors = $this->validate($fullName, $email, $message);

        // Если есть ошибки — возвращаем 422 с описанием
        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        // Сохраняем в БД (сырые данные — экранирование на выводе для защиты от XSS)
        $this->feedbackModel->save($fullName, $email, $message);

        $this->json(['success' => true]);
    }

    /**
     * Валидация поля формы
     * Проверки: не пустое, длина, формат email
     * Ошибки возвращаются ассоциативным массивом (поле -> текст ошибки)
     */
    private function validate(string $fullName, string $email, string $message): array
    {
        $errors = [];

        // ФИО: обязательное, не более 255 символов
        if ($fullName === '') {
            $errors['full_name'] = 'Поле ФИО обязательно для заполнения.';
        } elseif (mb_strlen($fullName) > 255) {
            $errors['full_name'] = 'ФИО не должно превышать 255 символов.';
        }

        // Email: обязательное, корректный формат, не более 255 символов
        if ($email === '') {
            $errors['email'] = 'Поле Email обязательно для заполнения.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Укажите корректный email адрес.';
        } elseif (mb_strlen($email) > 255) {
            $errors['email'] = 'Email не должен превышать 255 символов.';
        }

        // Сообщение: обязательное, не более 5000 символов
        if ($message === '') {
            $errors['message'] = 'Поле сообщения обязательно для заполнения.';
        } elseif (mb_strlen($message) > 5000) {
            $errors['message'] = 'Сообщение не должно превышать 5000 символов.';
        }

        return $errors;
    }

}
