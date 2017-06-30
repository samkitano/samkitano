{{-- FRONTEND HOME PAGE - Expects $content --}}

@extends('layouts.front')

@section('title', 'Home')
@section('section_name', 'Home')
@section('content')
    <div class="Home-page">
        @if ($content)
            <div class="Home-page__Content">
                <h1>{{ $content->title }}</h1>

                {!! $content->body !!}
            </div>
        @endif

        @if ($latest)
            <div class="Home-page__Latest">
                <h1>Latest Blog Posts</h1>

                <div class="Articles__List">
                    @foreach($latest as $post)
                        <div class="Articles__List Article Article_{{ digitizeDate($post['created']) }}">
                            <div class="Article__Heading">
                                <div class="Article__Heading_link">
                                    <a href="/articles/{{ $post['slug'] }}">{{ $post['title'] }}</a>
                                </div>

                                <div class="Article__Heading_text Pg-header">
                                    {{ $post['subtitle'] }}
                                </div>
                            </div>

                            <div class="Article__Content">
                                <div class="Article__Content_partial">
                                    <p>{{ $post['teaser'] }}</p>
                                </div>
                            </div>

                            <div class="Article__Footer">
                                <div class="Article__Footer_top">
                                    @include('front.partials._tags', ['links' => false, 'tags' => explode(',', $post['tags'])])
                                </div>

                                <div class="Article__Footer_bottom">
                                    <div class="Article__Footer__Icon Article__Footer__Icon_likes">
                                        <div class="Svg hint hint--top-right" aria-label="Likes">
                                            <svg class="Svg__Icon Svg__Icon_unclickable"
                                                 role="img"
                                                 width="16"
                                                 height="16"
                                                 viewBox="0 0 20 20"
                                                    >{!! PATH_SVG_LIKE !!}<title>Likes</title
                                            ></svg><span>&nbsp;{{ $post['likes']['total'] }}</span>
                                        </div>
                                    </div>

                                    <div class="Article__Footer__Icon Article__Footer__Icon_comments">
                                        <div class="Svg hint hint--top-right" aria-label="Comments">
                                            <svg class="Svg__Icon Svg__Icon_unclickable"
                                                 role="img"
                                                 width="16"
                                                 height="16"
                                                 viewBox="0 0 24 24"
                                                    >{!! PATH_SVG_COMMENT !!}<title>Comments</title
                                            ></svg><span>&nbsp;{{ $post['comments']['total'] }}</span>
                                        </div>
                                    </div>

                                    <div class="Article__Footer__Icon Article__Footer__Icon_dates">
                                        <div class="Svg">
                                            <svg class="Svg__Icon Svg__Icon_unclickable"
                                                 role="img"
                                                 width="16"
                                                 height="16"
                                                 viewBox="0 0 60 60"
                                                    >{!! PATH_SVG_CALENDAR !!}<title>Dates</title
                                            ></svg><time pubdate
                                                         datetime="{{ $post['created'] }}"
                                                    >&nbsp;{{ Carbon\Carbon::parse($post['created'])
                                                                           ->format('d-M-Y') }}</time
                                                    ><span>&nbsp;({{ Carbon\Carbon::createFromTimeStamp(
                                                        strtotime($post['created']))->diffForHumans() }})</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@stop
