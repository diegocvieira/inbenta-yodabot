export default {
    template: `
        <div id="chat-form-container">
            <div v-show="writing" class="writing">YodaBot is writing...</div>
            <div v-show="error" class="error">{{ error }}</div>

            <form @submit.prevent="submitForm" id="chat-form">
                <div class="field is-grouped" style="width: 100%;">
                    <div class="control is-expanded">
                        <input type="text" name="message" v-model="message" class="input" placeholder="type your message..." required autofocus />
                    </div>

                    <div class="control">
                        <button type="submit" class="button is-primary" :disabled="writing">Send!</button>
                    </div>
                </div>
            </form>
        </div>
    `,
    data () {
        return {
            response: '',
            message: '',
            writing: false,
            error: false
        }
    },
    methods: {
        submitForm: function () {
            if (!this.message) {
                return
            }

            this.writing = true
            this.error = false
            this.$emit('message', this.message)

            axios
                .post('/api/conversation/message', {
                    message: this.message
                })
                .then(response => {
                    this.storageNoResultsCount(response.data)

                    if (this.noResultsCountIsReached()) {
                        return this.getCharacters()
                    } else {
                        this.$emit('response', response.data)
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 400) {
                        setTimeout(() => {
                            localStorage.removeItem('conversation_history');
                        }, 200);

                        this.error = error.response.data.error
                    } else {
                        this.error = 'An unexpected error has occurred. Please, try again later.'
                    }
                })
                .finally(() => {
                    this.writing = false
                })

            this.message = ''
        },
        getCharacters() {
            return axios
                .get('/api/conversation/characters')
                .then(response => {
                    this.$emit('response', response.data)
                })
        },
        noResultsCountIsReached() {
            const maxAttempts = 2

            if (localStorage.getItem('no_results') && parseInt(localStorage.getItem('no_results')) >= maxAttempts) {
                return true
            } else {
                return false
            }
        },
        storageNoResultsCount(response) {
            if (response.data.flags && response.data.flags.includes('no-results'))  {
                localStorage.setItem('no_results', localStorage.getItem('no_results') ? parseInt(localStorage.getItem('no_results')) + 1 : 1)
            } else {
                localStorage.removeItem('no_results');
            }
        }
    }
}
