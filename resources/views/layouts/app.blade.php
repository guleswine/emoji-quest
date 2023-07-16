<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="favicon.svg" type="image/svg">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
    </body>
</html>

<style>
    body {
        cursor: url('/cursor.png') 0 0, default;
    }
    a, button {
        cursor: url('/hold_cursor.png') 12 4, pointer;
    }
    .draggable {
        cursor: url('/drag_cursor_small.png') 0 0, grab;
    }


</style>
