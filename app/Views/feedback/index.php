<!DOCTYPE html>
<!--
  Представление для страницы обратной связи

-->
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма обратной связи</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Заголовок формы -->
        <h1>Обратная связь</h1>
        <p class="subtitle">Мы будем рады вашим отзывам и предложениям</p>

        <!-- Форма отправки сообщения (novalidate — полагаемся на серверную валидацию) -->
        <form id="feedback-form" class="feedback-form" novalidate>
            <!-- Поле ФИО -->
            <div class="form-group">
                <label for="full_name">ФИО</label>
                <input type="text" id="full_name" name="full_name" placeholder="Иванов Иван Иванович">
                <span class="error" id="error-full_name"></span>
                <!-- Сюда JS выводит ошибку валидации для этого поля -->
            </div>

            <!-- Поле телефона -->
            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="tel" id="phone" name="phone" placeholder="+7XXXXXXXXXX">
                <span class="error" id="error-phone"></span>
            </div>

            <!-- Поле Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com">
                <span class="error" id="error-email"></span>
            </div>

            <!-- Поле сообщения -->
            <div class="form-group">
                <label for="message">Сообщение</label>
                <textarea id="message" name="message" rows="5" placeholder="Текст сообщения..."></textarea>
                <span class="error" id="error-message"></span>
            </div>

            <!-- Кнопка отправки с SVG-иконкой -->
            <button type="submit" class="btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>Отправить</span>
            </button>
        </form>

        <!-- Блок успешной отправки (скрыт по умолчанию) -->
        <div id="form-success" class="success hidden"></div>

        <!-- Разделитель -->
        <div class="divider">Сообщения</div>

        <!-- Список сообщений -->
        <h2>Отправленные сообщения</h2>
        <div id="messages-list">
            <?php if (empty($messages)): ?>
                <p class="empty">Сообщений пока нет.</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <!-- Карточка одного сообщения -->
                    <div class="message">
                        <div class="message-header">
                            <div class="message-author">
                                <!-- Аватар — первая буква имени -->
                                <span class="avatar"><?= mb_strtoupper(mb_substr($msg['full_name'], 0, 1)) ?></span>
                                <strong><?= htmlspecialchars($msg['full_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </div>
                            <!-- Дата отправки -->
                            <span class="date"><?= htmlspecialchars($msg['created_at'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <div class="message-phone"><?= htmlspecialchars($msg['phone'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="message-email"><?= htmlspecialchars($msg['email'], ENT_QUOTES, 'UTF-8') ?></div>
                        <!-- Текст сообщения (nl2br — перенос строк) -->
                        <div class="message-body"><?= nl2br(htmlspecialchars($msg['message'], ENT_QUOTES, 'UTF-8')) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Подключаем JavaScript (ES6-класс с fetch) -->
    <script src="/public/js/app.js"></script>
</body>
</html>
