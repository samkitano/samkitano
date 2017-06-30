{{-- ARTICLE DATES --}}
{{-- PATHS FOR SVGs ARE IN _helpers.php --}}

<div class="Article__Dates">
    <div class="Article__Dates__Icon">
        <div class="Svg">
            <svg class="Svg__Icon"
                 role="img"
                 aria-label="Published"
                 width="32"
                 height="32"
                 viewBox="0 0 60 60"
                    >{!! PATH_SVG_CALENDAR !!}<title>Published</title
            ></svg>
        </div>
    </div>

    <div class="Article__Dates__Time">
        <div class="Article__Dates__Time__Created">
            <time pubdate
                  datetime="{{ $article['created'] }}"
                    >Published: {{ Carbon\Carbon::parse($article['created'])->format('d-M-Y') }}</time
            >

            @if ($humanized)
                <span class="Article__Dates__Time__Created_humanized"
                        >&nbsp;({{ Carbon\Carbon::createFromTimeStamp(strtotime($article['created']))
                                                ->diffForHumans() }})</span
                >
            @endif
        </div>

        <div class="Article__Dates__Time__Updated">
            Last Update: {{ Carbon\Carbon::parse($article['updated'])->format('d-M-Y') }}
            @if ($humanized)
                <span class="Article__Dates__Time__Updated_humanized"
                        >&nbsp;({{ Carbon\Carbon::createFromTimeStamp(strtotime($article['updated']))
                                                ->diffForHumans() }})</span
                >
            @endif
        </div>
    </div>
</div>
