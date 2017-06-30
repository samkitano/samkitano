{{-- ARTICLE LIKES PARTIAL --}}
{{-- PATHS FOR SVGs ARE IN _helpers.php --}}

<div class="Article__Social_like hint hint--top-right"
     aria-label="{{ $likes['ariaText'] }}">
    <div class="Svg Button Button_default Button_small{{ $likes['inactive'] ? ' Button_inactive' : '' }}">
        <svg class="{{ $likes['iconClass'] }}"
             data-social-media="facebook"
             role="img"
             onclick="like(event)"
             aria-article="{{ $slug }}"
             width="42"
             height="42"
             viewBox="-3 -3 26 26"
                >{!! PATH_SVG_LIKE !!}<title>Like This Article</title
        ></svg>
    </div>
</div>
