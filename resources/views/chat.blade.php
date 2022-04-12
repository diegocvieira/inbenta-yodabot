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
                        <chat-component></chat-component>
                    </div>
                </div>
            </div>
        </main>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.14/vue.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

