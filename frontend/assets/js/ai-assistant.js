/**
 * Concierge Koketsu - AI Assistant Logic
 * Gestão de chat e comunicação com a micro-api Node.js
 */

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('ai-toggle-btn');
    const closeBtn = document.getElementById('ai-close-btn');
    const chatWindow = document.getElementById('ai-chat-window');
    const chatForm = document.getElementById('ai-chat-form');
    const userInput = document.getElementById('ai-user-input');
    const chatMessages = document.getElementById('ai-chat-messages');

    let history = [];

    // Abrir/Fechar Chat
    toggleBtn.addEventListener('click', () => {
        chatWindow.classList.toggle('active');
        if (chatWindow.classList.contains('active')) {
            userInput.focus();
        }
    });

    closeBtn.addEventListener('click', () => {
        chatWindow.classList.remove('active');
    });

    // Enviar Mensagem
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = userInput.value.trim();
        if (!message) return;

        // Limpar input
        userInput.value = '';

        // Adicionar mensagem do usuário no UI
        appendMessage('user', message);

        // Mostrar indicador de "digitando..."
        const typingId = appendTypingIndicator();

        try {
            // Chamar Micro-API Node.js
            const response = await fetch('http://localhost:5000/api/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message, history })
            });

            const data = await response.json();

            // Remover indicador de digitando
            removeTypingIndicator(typingId);

            if (data.reply) {
                appendMessage('ai', data.reply);
                // Atualizar histórico para contexto contínuo
                history.push({ role: 'user', content: message });
                history.push({ role: 'assistant', content: data.reply });
                
                // Limitar histórico para não sobrecarregar tokens
                if (history.length > 10) history = history.slice(-10);
            } else {
                appendMessage('ai', 'Perdão, tive um pequeno problema técnico. Poderia repetir?');
            }

        } catch (error) {
            console.error('Erro AI:', error);
            removeTypingIndicator(typingId);
            appendMessage('ai', 'Estou com dificuldades de conexão no momento. Por favor, tente novamente em instantes.');
        }
    });

    function appendMessage(sender, text) {
        const div = document.createElement('div');
        div.className = sender === 'ai' 
            ? 'message-ai p-3 text-xs leading-relaxed max-w-[85%]' 
            : 'message-user p-3 text-xs leading-relaxed max-w-[85%] ml-auto';
        
        if (sender === 'ai') {
            // Transformar [PRODUTO] em link clicável
            const linkedText = text.replace(/\[([^\]]+)\]/g, (match, productName) => {
                return `<a href="pages/catalogo.html?search=${encodeURIComponent(productName)}" class="text-[var(--brand-yellow)] bg-black/10 px-1 rounded font-bold hover:bg-[var(--brand-yellow)] hover:text-black transition-all decoration-none inline-block my-1">${productName} <i class="bi bi-arrow-right-short"></i></a>`;
            });
            div.innerHTML = linkedText;
        } else {
            div.textContent = text;
        }

        chatMessages.appendChild(div);
        
        // Scroll para o fundo
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendTypingIndicator() {
        const id = 'typing-' + Date.now();
        const div = document.createElement('div');
        div.id = id;
        div.className = 'message-ai p-3 text-xs max-w-[50px] flex space-x-1';
        div.innerHTML = `
            <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce"></span>
            <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1 h-1 bg-gray-400 rounded-full animate-bounce [animation-delay:0.4s]"></span>
        `;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }
});
