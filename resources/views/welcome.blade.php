<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cnj test</title>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content" id="app">
                <upload-form></upload-form>
            </div>
        </div>
        <script src="{{ mix('/js/app.js') }}"></script>
    </body>
</html>
