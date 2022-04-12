import ChatFormComponent from './ChatFormComponent.vue'

export default {
    template: `
        <div id="chat">
            <ul id="chat-messages">
                <li v-for="message in messages">
                    <b v-if="message.bot">YodaBot:</b> <b v-else>Me:</b>
                    <p v-html="message.body"></p>
                </li>
            </ul>

            <chat-form-component @message="saveUserMessage" @response="saveBotMessage"></chat-form-component>
        </div>
    `,
    components:{
        'chat-form-component': ChatFormComponent
    },
    data () {
        return {
            messages: []
        }
    },
    methods: {
        saveBotMessage(response) {
            let message = ''

            if (Array.isArray(response.data.message)) {
                message = this.formatStarWarsResponse(response.data.message, response.data.type)
            } else {
                message = response.data.message
            }

            this.messages.push({body: message, bot: true})
            this.setConversationHistory()
        },
        saveUserMessage(message) {
            this.messages.push({body: message, bot: false})
            this.setConversationHistory()
        },
        formatStarWarsResponse(response, type) {
            let data = type == 'films' ? `The <b>force</b> is in this movies:` : `I haven't found any results, but here is a list os some Star Wars characters:`

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
        }
    },
    created: function () {
        this.messages = this.getConversationHistory()
    },
    watch: {
        messages: function() {
            setTimeout(function() {
                const chatMessagesDiv = document.getElementById('chat-messages');
                chatMessagesDiv.scrollTop = chatMessagesDiv.scrollHeight;
            }, 20)
        }
    }
}
