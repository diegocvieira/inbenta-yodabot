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
            writing: false
        }
    },
    methods: {
        submitForm: function (event) {
            this.messages.push({body: this.message, bot: false})
            this.writing = true

            axios
                .post('/api/messages/send', {
                    message: this.message
                })
                .then(response => {
                    console.log(response.data.data)

                    if (Array.isArray(response.data.data)) {
                        data = formatStarWarsResponse(response.data.data)
                    } else {
                        data = response.data.data
                    }

                    this.messages.push({body: data, bot: true})
                })
                .catch(error => {
                    if (error.response && error.response.status === 400) {
                        console.log(error.response.data.error)
                    }

                    // console.log(error)
                })
                .finally(() => {
                    this.writing = false
                })

            this.message = ''

            event.preventDefault()
        }
    }
})

function formatStarWarsResponse(response) {
    data = `I haven't found any results, but here is a list os some Star Wars characters:`
    data += '<ul>'

    response.forEach(function(value) {
        data += '<li>' + value + '</li>'
    })

    data += '</ul>'

    return data;
}
