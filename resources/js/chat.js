import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-message');
    const conversationIdInput = document.getElementById('conversation-id');
    const tokenInput = document.getElementById('chat-token');

    if (!chatBox || !form || !input || !conversationIdInput || !tokenInput) {
        console.error('Chat elements are missing.');
        return;
    }

    let currentChannelName = null;

    function getConversationId() {
        return conversationIdInput.value.trim();
    }

    function getToken() {
        return tokenInput.value.trim();
    }

    function listenToConversation() {
        const conversationId = getConversationId();

        if (!conversationId) {
            return;
        }

        if (currentChannelName) {
            window.Echo.leave(currentChannelName);
        }

        currentChannelName = `private-chat.${conversationId}`;

        window.Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (event) => {
                appendMessage(event.message);
            });

        console.log(`Listening privately to chat.${conversationId}`);
    }

    conversationIdInput.addEventListener('change', () => {
        listenToConversation();
    });

    conversationIdInput.addEventListener('blur', () => {
        listenToConversation();
    });

    if (getConversationId()) {
        listenToConversation();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const conversationId = getConversationId();
        const token = getToken();
        const body = input.value.trim();

        if (!conversationId || !token || !body) {
            alert('Conversation ID, token, and message are required.');
            return;
        }

        input.value = '';

        const response = await fetch(`/api/chat/conversations/${conversationId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({
                body: body,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            appendMessage(data.chat_message);
        } else {
            console.error(data);
            alert(data.message || 'Message could not be sent.');
        }
    });

    function getSenderName(sender) {
        if (!sender) {
            return 'Unknown User';
        }

        const firstName = sender.first_name ?? '';
        const lastName = sender.last_name ?? '';
        const fullName = `${firstName} ${lastName}`.trim();

        return fullName || sender.username || sender.email || 'Unknown User';
    }

    function appendMessage(message) {
        const item = document.createElement('div');
        item.classList.add('message-item');

        item.innerHTML = `
            <div class="message-sender">${getSenderName(message.sender)}</div>
            <div class="message-body">${message.body}</div>
        `;

        chatBox.appendChild(item);
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
