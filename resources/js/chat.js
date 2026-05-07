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

    let currentChannel = null;

    function listenToConversation(conversationId) {
        if (currentChannel) {
            window.Echo.leave(currentChannel);
        }

        currentChannel = `conversation.${conversationId}`;

        window.Echo.channel(currentChannel)
            .listen('.message.sent', (event) => {
                appendMessage(event.message);
            });

        console.log(`Listening to ${currentChannel}`);
    }

    document.getElementById('start-listening').addEventListener('click', () => {
        const conversationId = conversationIdInput.value.trim();

        if (!conversationId) {
            alert('Please enter conversation ID.');
            return;
        }

        listenToConversation(conversationId);
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const conversationId = conversationIdInput.value.trim();
        const token = tokenInput.value.trim();
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
            console.log('Message sent successfully.', data.chat_message);
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
