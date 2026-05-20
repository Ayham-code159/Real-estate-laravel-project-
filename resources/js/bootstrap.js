import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

function getChatToken() {
    return localStorage.getItem('servixa_chat_token') || '';
}

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,

    authEndpoint: '/api/broadcasting/auth',

    auth: {
        headers: {
            Accept: 'application/json',
            Authorization: `Bearer ${getChatToken()}`,
        },
    },
});
