{{-- FRONTEND DEFAULT CONTENT SECTION --}}

<div class="Default">
    @include('layouts.partials._front-hero')

    @if ($isNotResetPw)
        @include('layouts.partials._front-nav', ['isArticles' => $isArticles])
    @endif

    @if ($isArticles)
        @yield('content')
    @else
        <section id="App" class="App-container @yield('section_name')">
            @yield('content')
        </section>
    @endif
</div>
