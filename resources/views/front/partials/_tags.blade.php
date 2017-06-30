{{-- ARTICLES TAGS --}}

@if (count($tags))
    <div class="Article__Footer__Icon Article__Footer__Icon_tags">
        <div class="Tags">
            <ul class="Tags__List">
                @foreach ($tags as $tag)
                    <li class="Tag Tag_{{ trim(strtolower($tag)) }}">
                        @if ($links)
                            <a href="/articles/tagged/{{ $tag }}">{{ $tag }}</a>
                        @else
                            <span class="No-link">{{ $tag }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
