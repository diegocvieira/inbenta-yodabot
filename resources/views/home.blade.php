<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8">

        <title>YodaBot</title>

        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <main id="app">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-7">
                        <div id="chat">
                            <ul id="chat-messages">
                                <li v-for="message in messages">
                                    <b v-if="message.bot">YodaBot:</b> <b v-else>Me:</b>
                                    <p v-html="message.body"></p>
                                </li>
                            </ul>

                            <div v-show="writing">YodaBot is writing...</div>

                            <form action="{{ route('api.messages.send') }}" method="POST" @submit="submitForm" id="chat-form">
                                <div class="field is-grouped" style="width: 100%;">
                                    <div class="control is-expanded">
                                        <input type="text" name="message" v-model="message" class="input" placeholder="type your message..." required />
                                    </div>

                                    <div class="control">
                                        <button type="submit" class="button is-primary">Send!</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

