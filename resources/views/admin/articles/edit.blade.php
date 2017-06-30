@php
    $tags = getAllTags();
@endphp

@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::articles.update', $article->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">Title</label>
                                <input class="form-control"
                                       type="text"
                                       name="title"
                                       id="title"
                                       autofocus
                                       value="{{ old('title') ? old('title') : $article->title }}"
                                >

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('subtitle') ? ' has-error' : '' }}">
                                <label for="subtitle">Subtitle</label>
                                <input class="form-control"
                                       type="text"
                                       name="subtitle"
                                       id="subtitle"
                                       value="{{ old('subtitle') ? old('subtitle') : $article->subtitle }}"
                                >
                            </div>

                            @if ($errors->has('subtitle'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subtitle') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <select id="tags"
                                        name="tags[]"
                                        class="select-tags form-control"
                                        style="width:100%!important;"
                                        multiple="multiple">
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}"
                                            @foreach($article->tags as $atag)
                                                @if($tag->id == $atag->id)
                                                    selected="selected"
                                                @endif
                                            @endforeach
                                        >{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label>
                                    <input name="published"
                                           id="published"
                                           type="checkbox"
                                           value="{{ old('published') ? old('published') : $article->published }}"
                                            {{ $article->published ? 'checked="checked"' : '' }}
                                    > Published</label
                                >
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body">Content</label>
                                <textarea class="form-control html"
                                          rows="20"
                                          id="body"
                                          name="body"
                                >{{ old('body') ? old('body') : $article->body }}</textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
