@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="{{ $media->type == 'image' ? 'col-sm-6' : 'col-xs-12' }}">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Media</h4></th>
                    </tr>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($media->toArray() as $key => $val)
                        <tr>
                            @if(! is_array($val))
                                <td>{{ $key }}</td>

                                @if($key == 'created_at')
                                    <td><span class="label label-primary"
                                                >{{ $media->created_at->format('d-m-Y') }}</span
                                        > <span class="label label-info">{{ $media->created_at->format('H:i:s') }}</span
                                          ></td
                                    >
                                @elseif($key == 'updated_at')
                                    <td><span class="label label-primary"
                                                >{{ $media->updated_at->format('d-m-Y') }}</span
                                        > <span class="label label-info">{{ $media->updated_at->format('H:i:s') }}</span
                                          ></td
                                    >
                                @else
                                    <td>{!! $val !!}</td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if ($media->type == 'image')
                <div class="col-sm-6">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><h4>Image</h4></th>
                        </tr>
                        <tr>
                            <th>Large Thumbnail</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <img src="{{ url(\App\Kitano\MediaManager\Manager::$mediaFolder
                                                . '/'
                                                . $media->album->name
                                                . '/thumbs/large_'
                                                . $media->file_name) }}"
                                     alt="{{ $media->name }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Medium Thumbnail</th>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{ url(\App\Kitano\MediaManager\Manager::$mediaFolder
                                                . '/'
                                                . $media->album->name
                                                . '/thumbs/medium_'
                                                . $media->file_name) }}"
                                     alt="{{ $media->name }}">
                            </td>
                        </tr>
                        <tr>
                            <th>Small Thumbnail</th>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{ url(\App\Kitano\MediaManager\Manager::$mediaFolder
                                                . '/'
                                                . $media->album->name
                                                . '/thumbs/small_'
                                                . $media->file_name) }}"
                                     alt="{{ $media->name }}">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if (isset($media->album_id) && $media->count() > 1)
            <hr>
            <a class="btn btn-primary"
               href="{{ route('admin::albums.edit', $media->album_id) }}">Reorder Media</a
            >
            <hr>
        @endif
    </section>
@stop
