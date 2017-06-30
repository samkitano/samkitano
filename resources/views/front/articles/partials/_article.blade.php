{{-- ARTICLE PARTIAL FOR ARTICLES LOOP --}}

<div class="Articles__List Article {{ digitizeDate($article['created']) }}">
    <div class="Article__Heading">
        <div class="Article__Heading_link">
            <a href="/articles/{{ $article['slug'] }}">{{ $article['title'] }}</a>
        </div>

        <div class="Article__Heading_text Pg-header">
            {{ $article['subtitle'] }}
        </div>
    </div>

    <div class="Article__Content">
        @if (! isset($article['content']))
            <div class="Article__Content_partial">
                <p>{{ $article['teaser'] }}</p>
            </div>
        @endif

        @if (isset($article['_snippetResult']))
            <div class="Article__Content_partial">
                <p>[...] {{ $article['_snippetResult']['body']['value'] }}</p>
            </div>
        @endif
    </div>

    <div class="Article__Footer">
        <div class="Article__Footer_top">
            @include('front.partials._tags', ['tags' => explode(',', $article['tags'])])
        </div>

        <div class="Article__Footer_bottom">
            @include('front.partials._icon-dates', ['article' => $article, 'humanized' => false])
            @include('front.partials._icon-likes', compact('article'))
            @include('front.partials._icon-comments', compact('article'))
        </div>
    </div>
</div>
