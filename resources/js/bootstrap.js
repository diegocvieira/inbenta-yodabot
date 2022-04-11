window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

new Vue({
    el: '#app',
    data () {
        return {
            messages: [],
            message: '',
            writing: false,
            error: false
        }
    },
    methods: {
        formatStarWarsResponse(response, type) {
            data = type == 'films' ? `The <b>force</b> is in this movies:` : `I haven't found any results, but here is a list os some Star Wars characters:`
            data += '<ul>'

            response.forEach(function(value) {
                data += '<li>' + value + '</li>'
            })

            data += '</ul>'

            return data;
        },
        getConversationHistory() {
            const conversationHistory = localStorage.getItem('conversation_history')
            return conversationHistory ? JSON.parse(conversationHistory) : []
        },
        setConversationHistory() {
            localStorage.setItem('conversation_history', JSON.stringify(this.messages))
        },
        clearConversationHistory() {
            localStorage.removeItem('conversation_history');
        },
        scrollToChatBottom() {
            const chatMessagesDiv = document.getElementById('chat-messages');
            chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
        },
        getCharacters() {
            return axios
                .get('/api/messages/characters')
                .then(response => {
                    const data = this.formatStarWarsResponse(response.data.data.message, 'characters')
                    this.messages.push({body: data, bot: true})
                })
        },
        submitForm: function (event) {
            this.messages.push({body: this.message, bot: false})
            this.writing = true
            this.error = false

            axios
                .post('/api/messages/send', {
                    message: this.message
                })
                .then(response => {
                    if (response.data.data.flags && response.data.data.flags.includes('no-results'))  {
                        localStorage.setItem('no_results', localStorage.getItem('no_results') ? parseInt(localStorage.getItem('no_results')) + 1 : 1)
                    } else {
                        localStorage.removeItem('no_results');
                    }

                    if (localStorage.getItem('no_results') && parseInt(localStorage.getItem('no_results')) >= 2) {
                        return this.getCharacters()
                    } else {
                        if (Array.isArray(response.data.data.message)) {
                            data = this.formatStarWarsResponse(response.data.data.message, 'films')
                        } else {
                            data = response.data.data.message
                        }

                        this.messages.push({body: data, bot: true})
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 400) {
                        setTimeout(() => {
                            this.clearConversationHistory()
                        }, 200);

                        this.error = error.response.data.error
                    } else {
                        this.error = 'An unexpected error has occurred. Please, try again later.'
                    }
                })
                .finally(() => {
                    this.writing = false

                    this.scrollToChatBottom()
                    this.setConversationHistory()
                })

            this.message = ''

            event.preventDefault()
        }
    },
    created: function () {
        this.messages = this.getConversationHistory()

        setTimeout(() => {
            this.scrollToChatBottom()
        }, 200);
    }
})
