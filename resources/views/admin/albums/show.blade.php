@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Album</h4></th>
                    </tr>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($album->toArray() as $key => $val)
                        <tr>
                            @if(! is_array($val))
                                <td>{{ $key }}</td>
                                <td>{!! $val !!}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Media</h4></th>
                    </tr>
                    <tr>
                        <th>Media</th>
                        <th>Order</th>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if (isset($media->album_id) && $album->media->count() > 1)
            <hr>
            <a class="btn btn-primary"
               href="{{ route('admin::albums.edit', $media->album_id) }}">Reorder Media</a
            >
            <hr>
        @endif
    </section>
@stop
