@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::albums.update', $album->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Name</label>

                                <input class="form-control"
                                       type="text"
                                       name="name"
                                       id="name"
                                       autofocus
                                       value="{{ old('name') ? old('name') : $album->name }}"
                                >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
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

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="3"><h4>Media</h4></th>
                    </tr>
                    <tr>
                        <th>Media</th>
                        <th>Order</th>
                        <th>Move</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($album->media as $media)
                            <tr>
                                @if ($media->type == 'image')
                                    <td><img src="{{ url(\App\Kitano\MediaManager\Manager::$mediaFolder
                                                        . '/'
                                                        .$album->name
                                                        .'/thumbs/medium_'
                                                        .$media->file_name) }}"
                                             alt="{{ $media->name }}"></td>
                                @else
                                    <td>{{ $media->file_name }}</td>
                                @endif

                                <td>{{ $media->order }}</td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('admin::albums.media.up', [$album->id, $media->id]) }}">
                                        {{ csrf_field() }}
                                        <button class="btn btn-primary btn-xs"
                                                type="submit"
                                                {{ $media->order == 0 ? ' disabled' : '' }}>
                                            <i class="fa fa-arrow-up"></i>
                                        </button>
                                    </form>

                                    <form method="POST"
                                          action="{{ route('admin::albums.media.down', [$album->id, $media->id]) }}">
                                        {{ csrf_field() }}
                                        <button class="btn btn-primary btn-xs"
                                                type="submit"
                                                {{ count($album->media) - 1 == $media->order ? ' disabled' : '' }}>
                                            <i class="fa fa-arrow-down"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop
