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
        $fullName = trim($_POST['full_name'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $message  = trim($_POST['message'] ?? '');

        $errors = $this->validate($fullName, $phone, $email, $message);

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        $this->feedbackModel->save($fullName, $phone, $email, $message);

        $this->json(['success' => true]);
    }

    private function validate(string $fullName, string $phone, string $email, string $message): array
    {
        $errors = [];

        if ($fullName === '') {
            $errors['full_name'] = 'Поле ФИО обязательно для заполнения.';
        } elseif (mb_strlen($fullName) > 255) {
            $errors['full_name'] = 'ФИО не должно превышать 255 символов.';
        }

        if ($phone === '') {
            $errors['phone'] = 'Поле телефона обязательно для заполнения.';
        } elseif (!preg_match('/^(?:\+7|8)\d{10}$/', $phone)) {
            $errors['phone'] = 'Укажите корректный номер телефона: +7XXXXXXXXXX или 8XXXXXXXXXX.';
        }

        if ($email === '') {
            $errors['email'] = 'Поле Email обязательно для заполнения.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Укажите корректный email адрес.';
        } elseif (mb_strlen($email) > 255) {
            $errors['email'] = 'Email не должен превышать 255 символов.';
        }

        if ($message === '') {
            $errors['message'] = 'Поле сообщения обязательно для заполнения.';
        } elseif (mb_strlen($message) > 5000) {
            $errors['message'] = 'Сообщение не должно превышать 5000 символов.';
        }

        return $errors;
    }

}
