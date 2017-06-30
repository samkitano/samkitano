@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::media.update', $media->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                <label for="title">Description</label>
                                <input class="form-control"
                                       type="text"
                                       name="description"
                                       id="description"
                                       autofocus
                                       value="{{ old('description') ? old('description') : $media->description }}"
                                >

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                @if ($media->order != 0)
                                    <a class="btn btn-primary"
                                       href="{{ route('admin::albums.edit', $media->album_id) }}">Reorder Media</a>
                                @endif

                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
