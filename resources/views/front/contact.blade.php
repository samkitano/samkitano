{{-- FRONTEND CONTACT PAGE (VUE) --}}

@extends('layouts.front')

@section('title', 'Contact')
@section('section_name', 'Contact')
@section('content')
    <div class="Contact-page">
        <div class="Contact-page__Content">
            <h1>{{ $content->title }}</h1>

            {!! $content->body !!}

            <app-contact user="{{ json_encode(['user' => auth()->check() ? auth()->user() : false ]) }}"></app-contact>
        </div>
    </div>
@stop

@push('postScripts')
    <script src="/js/contact.js"></script>
@endpush
