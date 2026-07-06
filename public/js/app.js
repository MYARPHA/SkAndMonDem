'use strict';

class FeedbackApp {
    constructor() {
        this.form = document.getElementById('feedback-form');
        this.messagesList = document.getElementById('messages-list');
        this.successBlock = document.getElementById('form-success');

        this.init();
    }

    init() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        this.loadMessages();
        setInterval(() => this.loadMessages(), 10000);
    }

    clearErrors() {
        document.querySelectorAll('.error').forEach((el) => {
            el.textContent = '';
        });
        this.successBlock.classList.add('hidden');
    }

    showErrors(errors) {
        for (const [field, message] of Object.entries(errors)) {
            const errorEl = document.getElementById(`error-${field}`);
            if (errorEl) {
                errorEl.textContent = message;
            }
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        this.clearErrors();

        const formData = new FormData(this.form);

        try {
            const response = await fetch('/submit', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                this.successBlock.textContent = 'Сообщение успешно отправлено!';
                this.successBlock.classList.remove('hidden');
                this.form.reset();
                await this.loadMessages();
            } else {
                this.showErrors(result.errors);
            }
        } catch (error) {
            console.error('Ошибка отправки формы:', error);
        }
    }

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

    renderMessages(messages) {
        if (messages.length === 0) {
            this.messagesList.innerHTML = '<p class="empty">Сообщений пока нет.</p>';
            return;
        }

        const html = messages.map((msg) => {
            const initial = msg.full_name.charAt(0).toUpperCase();
            return `
                <div class="message">
                    <div class="message-header">
                        <div class="message-author">
                            <span class="avatar">${this.escapeHtml(initial)}</span>
                            <strong>${this.escapeHtml(msg.full_name)}</strong>
                        </div>
                        <span class="date">${this.escapeHtml(msg.created_at)}</span>
                    </div>
                    <div class="message-email">${this.escapeHtml(msg.email)}</div>
                    <div class="message-body">${this.escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
                </div>
            `;
        }).join('');

        this.messagesList.innerHTML = html;
    }

    escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FeedbackApp();
});
