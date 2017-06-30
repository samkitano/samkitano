<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.partials._seo')
    @include('layouts.partials._favicons')
    @include('layouts.scripts._l-head')

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    @include('front.allow-cookies')

    @if (isset($args))
        @include('layouts.partials._front-default-content', [
            'isArticles' => $args['isArticles'],
            'isNotResetPw' => $args['route'] != 'password.reset'
        ])

        @include('layouts.partials._front-footer')

        @stack('preScripts')
        @include('layouts.scripts._front-main-scripts')
        @stack('postScripts')
    @else
        <div class="Default">
            @include('layouts.partials._front-hero')

            <section id="App" class="App-container @yield('section_name')">
                @yield('content')
            </section>

            @include('layouts.partials._front-footer')
        </div>
    @endif

    @include('layouts.scripts._requirements')
    @include('layouts.partials._noscript')

    <script src="/js/privacy.js"></script>
</body>
