/**
 * newsletter.js - Gerenciamento de inscrição na newsletter
 */

(function () {
    'use strict';

    const NEWSLETTER_API = '/backend/api/newsletter/inscrever';

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formNewsletter');
        const emailInput = document.getElementById('newsletterEmail');
        const submitBtn = document.getElementById('btnNewsletter');

        if (!form) return;

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const email = emailInput.value.trim();
            if (!email) return;

            // Bloqueia o botão e mostra loading (se houver estilo para isso)
            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = '...';

            try {
                const response = await fetch(NEWSLETTER_API, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email_newsletter: email })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Sucesso', data.message);
                    form.reset();
                } else {
                    showToast('error', 'Erro', data.message || 'Falha ao se inscrever.');
                }
            } catch (error) {
                console.error('[Newsletter] Erro:', error);
                showToast('error', 'Erro de Conexão', 'Não foi possível completar a inscrição. Tente novamente mais tarde.');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    });

    /**
     * Função auxiliar para exibir o Toast (reutilizando a lógica do sistema)
     * Como o sistema usa PHP para gerar o toast no redirect, aqui criamos um dinamicamente via JS.
     */
    function showToast(type, label, message) {
        let container = document.getElementById('toast-container-js');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container-js';
            container.style.position = 'fixed';
            container.style.top = '20px';
            container.style.right = '20px';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        const icon = (type === 'success') ? '&#10003;' : '&#10005;';
        const toast = document.createElement('div');
        toast.className = `toast-balloon ${type}`;
        // Reutilizando os estilos CSS do backend/Views/Templates/auth/login.php
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-body">
                <strong>${label}</strong>
                <p>${message}</p>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        `;

        container.appendChild(toast);

        // Auto remove após 5 segundos
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'toastOut 0.4s ease forwards';
                setTimeout(() => toast.remove(), 400);
            }
        }, 5000);
    }

})();
