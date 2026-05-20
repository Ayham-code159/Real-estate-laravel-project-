import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-message');
    const conversationIdInput = document.getElementById('conversation-id');
    const tokenInput = document.getElementById('chat-token');
    const displayNameInput = document.getElementById('chat-display-name');
    const settingsToggle = document.getElementById('settings-toggle');
    const settingsPanel = document.getElementById('settings-panel');
    const statusLine = document.getElementById('connection-status');
    const typingIndicator = document.getElementById('typing-indicator');
    const voiceRecordBtn = document.getElementById('voice-record-btn');
    const imageUploadBtn = document.getElementById('image-upload-btn');
    const imageInput = document.getElementById('chat-image-input');

    if (!chatBox || !form || !input || !conversationIdInput || !tokenInput) {
        console.error('Chat elements are missing.');
        return;
    }

    let currentConversationId = null;
    let currentPrivateChannel = null;
    let typingTimeout = null;
    let lastTypingWhisperAt = 0;

    let mediaRecorder = null;
    let audioChunks = [];
    let isRecording = false;

    const params = new URLSearchParams(window.location.search);
    const conversationIdFromUrl = params.get('conversation_id');
    const savedToken = localStorage.getItem('servixa_chat_token');
    const savedDisplayName = localStorage.getItem('servixa_chat_display_name');

    if (conversationIdFromUrl) {
        conversationIdInput.value = conversationIdFromUrl;
    }

    if (savedToken) {
        tokenInput.value = savedToken;
    }

    if (savedDisplayName && displayNameInput) {
        displayNameInput.value = savedDisplayName;
    }

    settingsToggle?.addEventListener('click', () => {
        settingsPanel.classList.toggle('open');
    });

    tokenInput.addEventListener('input', () => {
        const token = tokenInput.value.trim();

        if (token) {
            localStorage.setItem('servixa_chat_token', token);
        }
    });

    displayNameInput?.addEventListener('input', () => {
        localStorage.setItem(
            'servixa_chat_display_name',
            displayNameInput.value.trim()
        );
    });

    function setStatus(text, type = 'waiting') {
        statusLine.innerHTML = `Status: <span class="status-${type}">${text}</span>`;
    }

    function getConversationId() {
        return conversationIdInput.value.trim();
    }

    function getToken() {
        return tokenInput.value.trim();
    }

    function clearEmptyState() {
        const empty = chatBox.querySelector('.empty-chat');
        if (empty) {
            empty.remove();
        }
    }

    function listenToConversation() {
        const conversationId = getConversationId();
        const token = getToken();

        if (!conversationId || !token) {
            setStatus('Waiting for conversation ID and token');
            return;
        }

        if (currentConversationId === conversationId) {
            return;
        }

        if (currentConversationId) {
            window.Echo.leave(`private-chat.${currentConversationId}`);
        }

        currentConversationId = conversationId;

        currentPrivateChannel = window.Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (event) => {
                appendMessage(event.message);

                const myUserId = getUserIdFromToken();
                const senderId = event.message.sender_id ?? event.message.sender?.id;

                if (Number(senderId) !== Number(myUserId)) {
                    setTimeout(() => {
                        markConversationAsRead();
                    }, 300);
                }
            })

            .listen('.message.seen', (event) => {
                markMessagesAsSeenInUi(event.message_ids);
            })

            .listen('.message.reaction.updated', (event) => {
                updateMessageReactionsInUi(event.message_id, event.reactions);
            })

            .listenForWhisper('typing', (event) => {
                const myUserId = getUserIdFromToken();

                if (Number(event.user_id) === Number(myUserId)) {
                    return;
                }

                showTypingIndicator(event.name || 'User');
            });

        setStatus(`Listening privately to conversation #${conversationId}`, 'ok');

        loadMessages();
    }

    async function loadMessages() {
        const conversationId = getConversationId();
        const token = getToken();

        if (!conversationId || !token) {
            return;
        }

        try {
            const response = await fetch(
                `/api/chat/conversations/${conversationId}/messages`,
                {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                }
            );

            const data = await response.json();

            if (!response.ok) {
                console.error(data);
                return;
            }

            chatBox.innerHTML = '';

            data.messages.forEach((message) => {
                appendMessage(message);
            });

            chatBox.scrollTop = chatBox.scrollHeight;

            setTimeout(() => {
                markConversationAsRead();
            }, 300);

        }
        catch (error) {
            console.error(error);
        }
    }

    conversationIdInput.addEventListener('change', listenToConversation);
    conversationIdInput.addEventListener('blur', listenToConversation);
    tokenInput.addEventListener('blur', listenToConversation);

    listenToConversation();

    chatBox.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-reaction-message-id]');

        if (!button) {
            return;
        }

        const messageId = button.dataset.reactionMessageId;
        const emoji = button.dataset.emoji;

        await reactToMessage(messageId, emoji);
    });

    input.addEventListener('input', () => {
        sendTypingWhisper();
    });

    voiceRecordBtn?.addEventListener('click', async () => {
        if (!isRecording) {
            startVoiceRecording();
        } else {
            stopVoiceRecording();
        }
    });

    imageUploadBtn?.addEventListener('click', () => {
        imageInput?.click();
    });

    imageInput?.addEventListener('change', async () => {
        const file = imageInput.files?.[0];

        if (!file) {
            return;
        }

        await uploadImageMessage(file);

        imageInput.value = '';
    });

    async function startVoiceRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true,
            });

            mediaRecorder = new MediaRecorder(stream);

            audioChunks = [];

            mediaRecorder.ondataavailable = (event) => {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(audioChunks, {
                    type: 'audio/webm',
                });

                await uploadVoiceMessage(audioBlob);

                stream.getTracks().forEach(track => track.stop());
            };

            mediaRecorder.start();

            isRecording = true;

            voiceRecordBtn.classList.add('recording');
            voiceRecordBtn.textContent = '⏹';
        }
        catch (error) {
            console.error(error);
            alert('Microphone access denied.');
        }
    }

    function stopVoiceRecording() {
        if (!mediaRecorder) {
            return;
        }

        mediaRecorder.stop();

        isRecording = false;

        voiceRecordBtn.classList.remove('recording');
        voiceRecordBtn.textContent = '🎤';
    }

    async function uploadVoiceMessage(audioBlob) {
        const conversationId = getConversationId();
        const token = getToken();

        const formData = new FormData();

        formData.append(
            'audio',
            audioBlob,
            'voice-message.webm'
        );

        try {
            const response = await fetch(
                `/api/chat/conversations/${conversationId}/messages`,
                {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: formData,
                }
            );

            const data = await response.json();

            if (!response.ok) {
                console.error(data);
                alert('Voice message upload failed.');
            }
        }
        catch (error) {
            console.error(error);
        }
    }

    async function uploadImageMessage(imageFile) {
        const conversationId = getConversationId();
        const token = getToken();

        if (!conversationId || !token) {
            settingsPanel.classList.add('open');
            alert('Conversation ID and token are required.');
            return;
        }

        const formData = new FormData();

        formData.append('image', imageFile);

        try {
            const response = await fetch(
                `/api/chat/conversations/${conversationId}/messages`,
                {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: formData,
                }
            );

            const data = await response.json();

            if (!response.ok) {
                console.error(data);
                alert('Image upload failed.');
            }
        }
        catch (error) {
            console.error(error);
            alert('Image upload failed.');
        }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const conversationId = getConversationId();
        const token = getToken();
        const body = input.value.trim();

        if (!conversationId || !token || !body) {
            settingsPanel.classList.add('open');
            alert('Conversation ID, token, and message are required.');
            return;
        }

        input.value = '';
        hideTypingIndicator();

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

        if (!response.ok) {
            console.error(data);
            alert(data.message || 'Message could not be sent.');
        }
    });

    function sendTypingWhisper() {
        if (!currentPrivateChannel) {
            return;
        }

        const now = Date.now();

        if (now - lastTypingWhisperAt < 900) {
            return;
        }

        lastTypingWhisperAt = now;

        currentPrivateChannel.whisper('typing', {
            user_id: getUserIdFromToken(),
            name: getUserNameFromToken(),
        });
    }

    function showTypingIndicator(name) {
        if (!typingIndicator) {
            return;
        }

        typingIndicator.textContent = `${name} is typing...`;
        typingIndicator.classList.add('active');

        clearTimeout(typingTimeout);

        typingTimeout = setTimeout(() => {
            hideTypingIndicator();
        }, 1800);
    }

    function hideTypingIndicator() {
        if (!typingIndicator) {
            return;
        }

        typingIndicator.classList.remove('active');
        typingIndicator.textContent = '';
    }

    function getUserIdFromToken() {
        try {
            const token = getToken();

            if (!token) {
                return null;
            }

            const payload = token.split('.')[1];

            if (!payload) {
                return null;
            }

            const decoded = JSON.parse(atob(payload));

            return decoded.sub ?? null;
        }
        catch {
            return null;
        }
    }

    function getUserNameFromToken() {
        const savedName = localStorage.getItem('servixa_chat_display_name');

        if (savedName) {
            return savedName;
        }

        const userId = getUserIdFromToken();

        return userId ? `User ${userId}` : 'User';
    }

    function appendMessage(message) {
        clearEmptyState();

        const row = document.createElement('div');
        row.classList.add('message-row');

        const myUserId = getUserIdFromToken();
        const senderId = message.sender_id ?? message.sender?.id;

        if (Number(senderId) === Number(myUserId)) {
            row.classList.add('mine');
        } else {
            row.classList.add('theirs');
        }

        const bubble = document.createElement('div');
        bubble.classList.add('message-bubble');

        const isMine = Number(senderId) === Number(myUserId);

        let contentHtml = '';

        if (message.message_type === 'voice' && message.audio_url) {
            contentHtml = `
                <audio controls class="voice-player">
                    <source src="${message.audio_url}" type="audio/webm">
                </audio>
            `;
        } else if (message.message_type === 'image' && message.image_url) {
            contentHtml = `
                <img
                    src="${message.image_url}"
                    class="chat-image"
                    alt="Chat image"
                >
            `;
        } else {
            contentHtml = `
                <div>${escapeHtml(message.body ?? '')}</div>
            `;
        }



      bubble.innerHTML = `
        ${contentHtml}

        <div class="message-meta">
            ${formatTime(message.created_at)}
            ${isMine ? `<span class="seen-ticks" data-message-id="${message.id}">${message.read_at ? '✓✓ Seen' : '✓'}</span>` : ''}
        </div>

        <div class="reaction-picker">
            ${reactionButtonHtml(message.id, '❤️')}
            ${reactionButtonHtml(message.id, '👍')}
            ${reactionButtonHtml(message.id, '😂')}
            ${reactionButtonHtml(message.id, '😮')}
            ${reactionButtonHtml(message.id, '😢')}
        </div>

        <div class="reactions-list" data-reactions-for="${message.id}">
            ${renderReactions(message.reactions ?? [])}
        </div>
    `;

        row.appendChild(bubble);
        chatBox.appendChild(row);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function reactionButtonHtml(messageId, emoji) {
        return `
            <button
                type="button"
                class="reaction-btn"
                data-reaction-message-id="${messageId}"
                data-emoji="${emoji}"
            >
                ${emoji}
            </button>
        `;
    }

    function renderReactions(reactions) {
        if (!Array.isArray(reactions) || reactions.length === 0) {
            return '';
        }

        const grouped = {};

        reactions.forEach((reaction) => {
            grouped[reaction.emoji] = (grouped[reaction.emoji] || 0) + 1;
        });

        return Object.entries(grouped)
            .map(([emoji, count]) => {
                return `<span class="reaction-chip">${emoji} ${count > 1 ? count : ''}</span>`;
            })
            .join('');
    }

    async function reactToMessage(messageId, emoji) {
        const conversationId = getConversationId();
        const token = getToken();

        if (!conversationId || !token) {
            settingsPanel.classList.add('open');
            alert('Conversation ID and token are required.');
            return;
        }

        try {
            const response = await fetch(
                `/api/chat/conversations/${conversationId}/messages/${messageId}/reactions`,
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify({
                        emoji: emoji,
                    }),
                }
            );

            const data = await response.json();

            if (!response.ok) {
                console.error(data);
                alert(data.message || 'Reaction failed.');
            }
        }
        catch (error) {
            console.error(error);
            alert('Reaction failed.');
        }
    }

    function updateMessageReactionsInUi(messageId, reactions) {
        const reactionsBox = document.querySelector(`[data-reactions-for="${messageId}"]`);

        if (!reactionsBox) {
            return;
        }

        reactionsBox.innerHTML = renderReactions(reactions);
    }

    function formatTime(value) {
        if (!value) {
            return 'now';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return 'now';
        }

        return date.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit',
        });
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    async function markConversationAsRead() {
        const conversationId = getConversationId();
        const token = getToken();

        if (!conversationId || !token) {
            return;
        }

        try {
            await fetch(`/api/chat/conversations/${conversationId}/read`, {
                method: 'PUT',
                headers: {
                    Accept: 'application/json',
                    Authorization: `Bearer ${token}`,
                },
            });
        } catch (error) {
            console.error(error);
        }
    }

    function markMessagesAsSeenInUi(messageIds) {
        if (!Array.isArray(messageIds)) {
            return;
        }

        messageIds.forEach((messageId) => {
            const tick = document.querySelector(`.seen-ticks[data-message-id="${messageId}"]`);

            if (tick) {
                tick.textContent = '✓✓ Seen';
            }
        });
    }
});
