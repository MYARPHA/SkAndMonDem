/**
 * Приложение обратной связи.
 * ES6-класс, использующий fetch API для отправки формы
 * и асинхронной загрузки списка сообщений
 */
'use strict';

class FeedbackApp {
    /** Инициализация: ссылки на DOM-элементы */
    constructor() {
        // Элементы формы
        this.form = document.getElementById('feedback-form');
        this.messagesList = document.getElementById('messages-list');
        this.successBlock = document.getElementById('form-success');

        this.init();
    }

    /** Навешиваем обработчики и запускаем первый опрос сообщений */
    init() {
        // Отправка формы через fetch (без перезагрузки страницы)
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        // Загружаем сообщения сразу при открытии страницы
        this.loadMessages();
        // Автообновление списка каждые 10 секунд
        setInterval(() => this.loadMessages(), 10000);
    }

    /** Очищает все ошибки и скрывает блок успеха */
    clearErrors() {
        document.querySelectorAll('.error').forEach((el) => {
            el.textContent = '';
        });
        this.successBlock.classList.add('hidden');
    }

    /** Показывает ошибки валидации под соответствующими полями */
    showErrors(errors) {
        // errors = { full_name: 'текст', phone: 'текст', email: 'текст', message: 'текст' }
        for (const [field, message] of Object.entries(errors)) {
            const errorEl = document.getElementById(`error-${field}`);
            if (errorEl) {
                errorEl.textContent = message;
            }
        }
    }

    /** Обработчик отправки формы: валидация на сервере, сохранение */
    async handleSubmit(e) {
        e.preventDefault();                 // Отменяем стандартную отправку
        this.clearErrors();                 // Сбрасываем предыдущие ошибки

        const formData = new FormData(this.form); // Собираем данные формы

        try {
            // Отправляем POST-запрос через fetch
            const response = await fetch('/submit', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json(); // Парсим JSON-ответ

            if (result.success) {
                // Успех: показываем уведомление и сбрасываем форму
                this.successBlock.textContent = 'Сообщение успешно отправлено!';
                this.successBlock.classList.remove('hidden');
                this.form.reset();
                await this.loadMessages();    // Обновляем список сообщений
            } else {
                // Ошибки валидации: отображаем под полями
                this.showErrors(result.errors);
            }
        } catch (error) {
            console.error('Ошибка отправки формы:', error);
        }
    }

    /** Загружает список сообщений с сервера через fetch */
    async loadMessages() {
        try {
            const response = await fetch('/list');
            const result = await response.json();

            if (result.success) {
                this.renderMessages(result.messages);
            }
        } catch (error) {
            console.error('Ошибка загрузки сообщений:', error);
        }
    }

    /** Формирует HTML для списка сообщений и вставляет в DOM */
    renderMessages(messages) {
        if (messages.length === 0) {
            this.messagesList.innerHTML = '<p class="empty">Сообщений пока нет.</p>';
            return;
        }

        // Собираем HTML для каждого сообщения
        const html = messages.map((msg) => {
            // Первая буква имени для аватара
            const initial = msg.full_name.charAt(0).toUpperCase();
            // Все данные экранируем через escapeHtml (защита от XSS)
            return `
                <div class="message">
                    <div class="message-header">
                        <div class="message-author">
                            <span class="avatar">${this.escapeHtml(initial)}</span>
                            <strong>${this.escapeHtml(msg.full_name)}</strong>
                        </div>
                            <span class="date">${this.escapeHtml(msg.created_at)}</span>
                        </div>
                        <div class="message-phone">${this.escapeHtml(msg.phone)}</div>
                        <div class="message-email">${this.escapeHtml(msg.email)}</div>
                    <div class="message-body">${this.escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
                </div>
            `;
        }).join('');

        this.messagesList.innerHTML = html;
    }

    /**
     * Экранирует HTML-спецсимволы в строке.
     * Использует DOM-метод createTextNode для безопасного экранирования.
     * Защита от XSS-атак при вставке пользовательских данных.
     */
    escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
}

// Запускаем приложение после полной загрузки DOM
document.addEventListener('DOMContentLoaded', () => {
    new FeedbackApp();
});
