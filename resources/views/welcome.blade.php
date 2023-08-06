<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="favicon.svg" type="image/svg">
        <title>Emoji Quest</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.dark\:text-gray-500{--tw-text-opacity:1;color:#6b7280;color:rgba(107,114,128,var(--tw-text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-200 dark:bg-gray-900 sm:items-center py-4 w-screen">
            <div class="fixed top-0 left-0  px-6 py-4 flex ">
                <a href="https://github.com/guleswine/emoji-quest" class="items-center inline-flex text-sm text-center text-gray-700 dark:text-gray-500 underline">
                    <img class="w-8 h-8" quest-q src="/open_emoji/lite_colored/github.png"/>Github
                </a>
                    <a href="https://www.openmoji.org" class="items-center inline-flex ml-4 text-center text-sm text-gray-700 dark:text-gray-500 underline">
                        <img class="w-8 h-8" quest-q src="/open_emoji/lite_colored/copyright.png"/>
                        Open Emoji
                    </a>
            </div>
            @if (Route::has('login'))
                <div class="fixed top-0 right-0 px-6 py-4 sm:block">

                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 fixed mt-14">
                <div class="flex place-content-center">
                    <img class="w-20 h-20 woman_standing" src="/open_emoji/svg/woman_standing.svg"/>
                    <img class="w-20 h-20 keyboard" src="/open_emoji/svg/keyboard.svg"/>
                    <img class="w-20 h-20 hammer" src="/open_emoji/svg/hammer.svg"/>
                    <img class="w-20 h-20 person_walking" src="/open_emoji/svg/person_walking.svg"/>
                    <div class="motorized_wheelchair w-20 h-20" style="background-image: url('/open_emoji/svg/motorized_wheelchair.svg');background-repeat: no-repeat;"></div>
                </div>
                <div class="flex place-content-center">
                    <img class="w-16 h-16 emoji-e" src="/open_emoji/svg/letter_E.svg"/>
                    <img class="w-16 h-16 emoji-m" src="/open_emoji/svg/letter_M.svg"/>
                    <img class="w-16 h-16 emoji-o" src="/open_emoji/svg/letter_O.svg"/>
                    <img class="w-16 h-16 emoji-j" src="/open_emoji/svg/letter_J.svg"/>
                    <img class="w-16 h-16 emoji-i" src="/open_emoji/svg/letter_I.svg"/>
                </div>
                <div class="flex place-content-center">
                    <img class="w-16 h-16 quest-q" src="/open_emoji/svg/letter_Q.svg"/>
                    <img class="w-16 h-16 quest-u" src="/open_emoji/svg/letter_U.svg"/>
                    <img class="w-16 h-16 quest-e" src="/open_emoji/svg/letter_E.svg"/>
                    <img class="w-16 h-16 quest-s" src="/open_emoji/svg/letter_S.svg"/>
                    <img class="w-16 h-16 quest-t" src="/open_emoji/svg/letter_T.svg"/>
                </div>


                @auth
                    <div class="pt-6 md:p-8 text-center space-y-4">
                        <a href="{{ url('/game') }}" class="text-4xl text-gray-700 dark:text-gray-500 focus:underline active:underline hover:underline">Play</a>
                    </div>
                @else
                <div class="pt-6 md:pt-8 text-center space-y-4">
                    <a href="{{ route('login') }}" class="items-center text-2xl inline-flex text-gray-700 dark:text-gray-500 focus:underline active:underline hover:underline">
                        <img class="h-10"  src="/open_emoji/lite_colored/id_button.png"/>Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="items-center ml-4 inline-flex text-2xl text-gray-700 dark:text-gray-500 focus:underline active:underline hover:underline">
                            <img class="h-10"  src="/open_emoji/lite_colored/new_button.png"/>Register</a>
                    @endif
                </div>
                    <div class="md:pt-8 text-center space-y-4">
                        <div class="">

                        <a href="guest" class="text-2xl inline-flex text-gray-700 dark:text-gray-500 focus:underline active:underline hover:underline">
                            <img class="w-8 h-8"  src="/open_emoji/lite_colored/video_game.png"/> Play as Guest</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </body>
</html>
<style>

    .motorized_wheelchair {
        position: relative;
        left: 167px;
        top:70px;
        animation-fill-mode: forwards;
        animation-name: motorized_wheelchair;
        animation-duration: 3s;
        animation-delay: 6s;
    }

    @keyframes motorized_wheelchair {
        0% { left: 162px; background-image: url('/open_emoji/svg/woman_in_motorized_wheelchair.svg') }
        33% {left: 162px;  background-image: url('/open_emoji/svg/woman_in_motorized_wheelchair.svg') }
        100% {left: 17px; background-image: url('/open_emoji/svg/woman_in_motorized_wheelchair.svg')  }
    }

    .woman_standing {
        position: relative;
        left: 530px;
        top:70px;
        animation-fill-mode: forwards;
        animation-name: woman_standing;
        animation-duration: 2s;
        animation-delay: 5s;
    }

    @keyframes woman_standing {
        0% {  }
        50% { left:500px; visibility: hidden;height: 4rem}
        100% {visibility: hidden   }
    }

    .keyboard {
        animation-name: keyboard;
        animation-delay: 1.5s;
        animation-iteration-count: 4;
        animation-duration: 1s;
    }

    @keyframes keyboard {
        0% {  transform: rotate( 0.0deg) }
        40% { transform: rotate(0.0deg) }
        60% { transform: rotate(10.0deg) }
        70% { transform: rotate(-10.0deg) }
        100% {  transform: rotate( 0.0deg) }
    }

    .hammer {
        position: relative;
        left: -30px;
        animation-name: hammer;
        animation-iteration-count: 4;
        animation-duration: 1s;
        animation-delay: 1.5s;
    }

    @keyframes hammer {
        0% {  transform: rotate( 0.0deg) }
        30% { transform: rotate(20.0deg) }
        60% { transform: rotate(-25.0deg) }
        100% {  transform: rotate( 0.0deg) }
    }


    .person_walking {
        position: relative;
        animation-name: person_walking;
        animation-duration: 1.5s;

        left: -80px;
    }

    @keyframes person_walking {
        0%   {left:0px; top:0px;}
        100%  {left:-80px; top:0px;}
    }

    .emoji-e {
        position: relative;
        animation-name: emoji-e;
        animation-delay: 2s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes emoji-e {
        0%   {left:80px; top:-60px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }
    .emoji-m {
        position: relative;
        animation-name: emoji-m;
        animation-delay: 3s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes emoji-m {
        0%   {left:0px; top:-60px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }

    .emoji-o {
        position: relative;
        animation-name: emoji-o;
        animation-delay: 4s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;

    }

    @keyframes emoji-o {
        0%   {left:-80px; top:-60px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }

    .emoji-j {
        position: relative;
        animation-name: emoji-j;
        animation-delay: 3s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;

    }

    @keyframes emoji-j {
        0%   {left:-160px; top:-60px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }

    .emoji-i {
        position: relative;
        animation-name: emoji-i;
        animation-delay: 3s;
        animation-duration: 6s;
        visibility: hidden;
        animation-fill-mode: forwards;

    }

    @keyframes emoji-i {
        0%   {left:-240px; top:-60px;height: 20px;visibility: visible}
        16.6%   {left:145px; top:0px;height: 4rem;visibility: visible}
        66.8%   {left:145px; top:0px;visibility: visible; transform: rotate(0.0deg)}
        100% {left:0px; top:0px;visibility: visible;transform: rotate(-180.0deg)}
    }


    .quest-q {
        position: relative;
        animation-name: quest-q ;
        animation-delay: 5s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes quest-q  {
        0%   {left:80px; top:-120px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }

    .quest-u {
        position: relative;
        animation-name: quest-u ;
        animation-delay: 2s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes quest-u  {
        0%   {left:0px; top:-120px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }
    .quest-e {
        position: relative;
        animation-name: quest-e ;
        animation-delay: 4s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes quest-e  {
        0%   {left:-80px; top:-120px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }
    .quest-s {
        position: relative;
        animation-name: quest-s ;
        animation-delay: 2s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes quest-s  {
        0%   {left:-160px; top:-120px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }
    .quest-t {
        position: relative;
        animation-name: quest-t ;
        animation-delay: 5s;
        animation-duration: 1s;
        visibility: hidden;
        animation-fill-mode: forwards;
    }

    @keyframes quest-t  {
        0%   {left:-240px; top:-120px;height: 20px;visibility: visible}
        100% {left:0px; top:0px;visibility: visible}
    }
</style>
