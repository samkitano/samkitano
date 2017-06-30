@extends('layouts.admin')

@section('content')
    <section class="resources">
        @if($isBoss)
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::statics.update', $article->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label for="slug">Slug</label>
                                <input class="form-control"
                                       type="text"
                                       name="slug"
                                       id="slug"
                                       autofocus
                                       value="{{ old('slug') ? old('slug') : $article->slug }}"
                                >
                            </div>

                            @if ($errors->has('slug'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('slug') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title">Title</label>
                                <input class="form-control"
                                       type="text"
                                       name="title"
                                       id="title"
                                       value="{{ old('title') ? old('title') : $article->title }}"
                                >
                            </div>

                            @if ($errors->has('subtitle'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="description">Title</label>
                                <input class="form-control"
                                       type="text"
                                       name="description"
                                       id="description"
                                       value="{{ old('description') ? old('description') : $article->description }}"
                                >
                            </div>

                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
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
        @else
            <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i
                        > Sorry, you don't have enough privileges to access this page.</h3
            >
        @endif
    </section>
@stop
