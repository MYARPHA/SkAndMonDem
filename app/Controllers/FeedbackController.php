<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    private Feedback $feedbackModel;

    public function __construct()
    {
        $this->feedbackModel = new Feedback();
    }

    public function index(): void
    {
        $messages = $this->feedbackModel->getAll();
        $this->render('feedback/index', ['messages' => $messages]);
    }

    public function list(): void
    {
        $messages = $this->feedbackModel->getAll();
        $this->json(['success' => true, 'messages' => $messages]);
    }

    public function submit(): void
    {
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $message  = trim($_POST['message'] ?? '');

        $errors = $this->validate($fullName, $email, $message);

        if (!empty($errors)) {
            $this->json(['success' => false, 'errors' => $errors], 422);
        }

        $this->feedbackModel->save(
            $this->sanitize($fullName),
            $this->sanitize($email),
            $this->sanitize($message)
        );

        $this->json(['success' => true]);
    }

    private function validate(string $fullName, string $email, string $message): array
    {
        $errors = [];

        if ($fullName === '') {
            $errors['full_name'] = 'Поле ФИО обязательно для заполнения.';
        } elseif (mb_strlen($fullName) > 255) {
            $errors['full_name'] = 'ФИО не должно превышать 255 символов.';
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

    private function sanitize(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
    }
}
