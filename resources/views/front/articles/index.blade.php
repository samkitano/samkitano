{{-- FRONTEND ARTICLES PAGE (VUE) --}}

@extends('layouts.front')

@section('title', 'Articles')
@section('section_name', 'Articles')
@section('content')
    @if (env('SCOUT_DRIVER') == 'algolia')
        <app-articles algolia
                      user="{{ json_encode(['user' => auth()->check() ? auth()->user() : false ]) }}"></app-articles>
    @else
        <app-articles user="{{ json_encode(['user' => auth()->check() ? auth()->user() : false ]) }}"></app-articles>
    @endif
@stop

@push('preScripts')
    <script>
        window.algolia = {!! json_encode([
                'appId' => env('ALGOLIA_APP_ID'),
                'appKey' => env('ALGOLIA_API_KEY')
            ]) !!};
    </script>
@endpush

@push('postScripts')
    <script src="/js/articles.js"></script>
@endpush
