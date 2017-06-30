{{-- ARTICLE TWITTER SHARE ICON --}}
{{-- PATHS FOR SVGs ARE IN _helpers.php --}}

<div class="Article__Social_twitter hint hint--top-right"
     aria-label="Share on Twitter">
    <a class="Svg Button Button_default Button_small"
       target="_blank"
       href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}">
        <svg class="Svg__Icon Svg__Icon_clickable"
             data-social-media="twitter"
             role="img"
             aria-article="{{ $article['slug'] }}"
             width="42"
             height="42"
             viewBox="0 0 29 29"
                >{!! PATH_SVG_TWITTER !!}/><title>Share on Twitter</title
        ></svg>
    </a>
</div>