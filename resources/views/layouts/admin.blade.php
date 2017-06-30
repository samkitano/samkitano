<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="NOINDEX,NOFOLLOW,NODIR,NOOP">
        <base href="/">

        <title>Sam Kitano - Admin</title>

        <link href="/css/admin.css" rel="stylesheet">

        @include('layouts.partials._favicons')

        @if (Session::has('file.download'))
            <meta http-equiv="refresh"
                  content="0;url={{ route('admin::file-download', Session::get('file.download')) }}">
        @endif
    </head>

    <body>
        @if (isset($pagename))
            @include('layouts.partials._admin-nav')
        @endif

        <div class="container-fluid">
            @if (isset($resourceName))
                <h2 class="Resource-head">{{ ucfirst($resourceName) }}</h2>
            @endif

            @yield('content')

            @include('layouts.partials._admin-footer')
        </div>

        @stack('preScripts')

        <script src="/js/manifest.js"></script>
        <script src="/js/vendor.js"></script>
        <script src="/js/base.js"></script>
        <script src="/js/dt.js"></script>
        <script src="/vendor/highlightjs/highlight.pack.js" type="text/javascript"></script>

        <script type="text/javascript">
            hljs.initHighlightingOnLoad();
        </script>

        @stack('postScripts')

        @include('layouts.partials.sweet-alert')
    </body>
</html>
