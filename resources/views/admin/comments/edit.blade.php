@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::comments.update', $comment->id) }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="_method" value="PATCH">

                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body">Body</label>
                                <textarea rows="15" class="form-control markdown"
                                       type="text"
                                       name="body"
                                       id="body"
                                >{{ old('body') ? old('body') : $comment->body }}</textarea>

                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xs-12 col-md-6">
                            <h3>Preview</h3>
                            <div class="markitup-container padded">
                                <div id="preview"
                                     class="markdown-preview">{{ old('body') ? old('body') : $comment->body }}</div>
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

@push('postScripts')
    <script>
        $('iframe').hide();
    </script>
@endPush