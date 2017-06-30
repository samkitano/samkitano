{{-- SEARCH ENGINE OPTIMIZATION SECTION --}}

@if (count($content))
    <title>{{ $content->title }} - {{ config('app.name') }}</title>
@elseif (isset($article))
    <title>{{ $article['title'] }} - {{ config('app.name') }}</title>
@else
    <title>@yield('title') - {{ config('app.name') }}</title>
@endif

<meta name="author" content="Sam Kitano">
<meta property="og:url" content="{{ request()->url() }}"/>
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary">
<meta name="twitter:domain" content="{{ env('APP_DOMAIN') }}">

@if ($args['resource'] == 'users')
    <meta name="robots" content="NOINDEX,NOFOLLOW,NODIR,NOOP">
@else
    <meta name="robots" content="INDEX,FOLLOW,NODIR,NOOP">
@endif

@if (isset($contentImage))
    <meta property="og:image" itemprop="image" content="{{ $contentImage }}"/>
@else
    <meta property="og:image" itemprop="image" content="{{ url('images/og.png') }}"/>
@endif

@if (count($content))
    <meta property="og:title" content="{{ $content->title }}"/>
    <meta name="twitter:title" property="og:title" itemprop="title name" content="{{ $content->title }}">
    <meta property="description" content="{{ $content->description }}"/>
    <meta name="twitter:description"
          property="og:description"
          itemprop="description"
          content="{{ $content->description }}">
@elseif(isset($article))
    <meta property="og:title" content="{{ $article['title'] }}"/>
    <meta name="twitter:title" property="og:title" itemprop="title name" content="{{ $article['title'] }}">
    <meta property="description" content="{{ $article['subtitle'] }}"/>
    <meta name="twitter:description"
          property="og:description"
          itemprop="description"
          content="{{ $article['subtitle'] }}">
@else
    <meta property="og:title" content="@yield('title')"/>
    <meta name="twitter:title" property="og:title" itemprop="title name" content="@yield('title')">
    <meta property="description" content="Sam Kitano's Personal Website"/>
    <meta name="twitter:description"
          property="og:description"
          itemprop="description"
          content="Sam Kitano's Personal Website">
@endif

<link rel="canonical" href="{{ request()->url() }}">
