{{-- FRONTEND INDIVIDUAL ARTICLE --}}

@extends('layouts.front')

@if (isset($article))
    @section('title', 'Article')
    @section('section_name', 'Article-view')
    @section('content')
        <article class="Article Article_{{ digitizeDate($article['created']) }}">
            <h1 class="Pg-header">{{ $article['title'] }}</h1>

            <div class="Article__Heading_text Pg-header">
                {{ $article['subtitle'] }}
            </div>

            <div class="Article__Heading_text Pg-header">
                @include('front.partials._icon-dates', ['article' => $article, 'humanized' => true])
            </div>

            <hr class="article">

            <div class="Article__Content_full">
                {!! $article['content'] !!}
            </div>

            <hr class="article">

            <div class="Article__Footer">
                <div class="Article__Footer_top">
                    @include('front.partials._tags', ['tags' => explode(',', $article['tags']), 'links' => false])
                </div>

                <div class="Article__Social">
                    @include('front.partials._icon-likes', ['slug' => $article['slug'], 'likes' => $likes])
                    @include('front.partials._icon-fb', ['slug' => $article['slug']])
                    @include('front.partials._icon-twitter', ['slug' => $article['slug']])
                </div>
            </div>

            <app-comments article-id="{{ $article['slug'] }}"
                          user="{{ json_encode(['user' => auth()->check() ? auth()->user() : false ]) }}">
            </app-comments>
        </article>
    @stop

    @push('postScripts')
        <script src="/js/likes.js"></script>
        <script src="/js/comments.js"></script>
        <script src="/vendor/highlightjs/highlight.pack.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    @endpush
@else
    @section('title', '404 - Not Found')
    @section('section_name', 'error')
    @section('content')
        <div>
            <h3>Not Found</h3>
            <hr>
            <p>No such article.</p>
            <br/>
            <a href="javascript:history.back()">Back</a>
        </div>
    @stop
@endif
