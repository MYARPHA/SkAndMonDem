<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма обратной связи</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Форма обратной связи</h1>

        <form id="feedback-form" class="feedback-form" novalidate>
            <div class="form-group">
                <label for="full_name">ФИО</label>
                <input type="text" id="full_name" name="full_name" placeholder="Иванов Иван Иванович" required>
                <span class="error" id="error-full_name"></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
                <span class="error" id="error-email"></span>
            </div>

            <div class="form-group">
                <label for="message">Сообщение</label>
                <textarea id="message" name="message" rows="5" placeholder="Текст сообщения..." required></textarea>
                <span class="error" id="error-message"></span>
            </div>

            <button type="submit" class="btn">Отправить</button>
        </form>

        <div id="form-success" class="success hidden"></div>

        <hr>

        <h2>Отправленные сообщения</h2>
        <div id="messages-list">
            <?php if (empty($messages)): ?>
                <p class="empty">Сообщений пока нет.</p>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message">
                        <div class="message-header">
                            <strong><?= $msg['full_name'] ?></strong>
                            <span class="date"><?= $msg['created_at'] ?></span>
                        </div>
                        <div class="message-email"><?= $msg['email'] ?></div>
                        <div class="message-body"><?= nl2br($msg['message']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="/public/js/app.js"></script>
</body>
</html>
