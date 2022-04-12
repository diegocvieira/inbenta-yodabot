window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import ChatComponent from './components/ChatComponent.vue'

new Vue({
    el: '#app',
    components: {
        'chat-component': ChatComponent
    }
})
