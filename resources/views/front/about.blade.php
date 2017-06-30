{{-- FRONTEND ABOUT PAGE - Expects $content --}}

@extends('layouts.front')

@section('title', 'About')
@section('section_name', 'About')
@section('content')
    <div class="About-page">
        <div class="About-page__Content">
            <h1>{{ $content->title }}</h1>

            {!! $content->body !!}
        </div>
    </div>
@stop

@push('postScripts')
    <script src="/vendor/highlightjs/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endpush
