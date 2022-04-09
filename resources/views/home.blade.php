<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">

        <title>YodaBot</title>
    </head>
    <body>
        <main id="app">
            <div id="chat">
                <ul id="chat-messages">
                    <li v-for="message in messages">
                        <b v-if="message.bot">YodaBot:</b> <b v-else>Me:</b>
                        <p v-html="message.body"></p>
                    </li>
                </ul>

                <div v-show="writing">YodaBot is writing...</div>

                <form action="{{ route('messages.send') }}" method="GET" @submit="submitForm" id="char-form">
                    <input type="text" name="message" v-model="message" required />
                    <button type="submit">Send!</button>
                </form>
            </div>
        </main>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

