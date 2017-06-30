{{-- ARTICLE FACEBOOK SHARE ICON --}}
{{-- PATHS FOR SVGs ARE IN _helpers.php --}}

<div class="Article__Social_facebook hint hint--top-right"
     aria-label="Share on Facebook">
    <a class="Svg Button Button_default Button_small"
       target="_blank"
       href="https://www.facebook.com/sharer/sharer.php?u={{ request()->url() }}">
        <svg class="Svg__Icon Svg__Icon_clickable"
             data-social-media="facebook"
             role="img"
             aria-article="{{ $article['slug'] }}"
             width="42"
             height="42"
             viewBox="0 0 1080 1080"
                >{!! PATH_SVG_FACEBOOK !!}<title>Share on Facebook</title
        ></svg>
    </a>
</div>
