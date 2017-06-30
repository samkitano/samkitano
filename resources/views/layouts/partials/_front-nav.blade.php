{{-- FRONTEND NAVIGATION (VUE) --}}

<app-nav alert="{{ session('status') ? json_encode(session('status')) : false }}"
         current="{{ $args['resource'] ? $args['resource'] : $args['method'] }}"
         {{ isset($article) ? 'is-article' : '' }}
         user="{{ json_encode(['user' => auth()->check() ? auth()->user() : false ]) }}">
</app-nav>

@push('postScripts')
    <script src="/js/front-nav.js"></script>
@endpush
