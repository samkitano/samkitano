@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_media_albums', $media->id) }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Media</th>
                        <th>Album</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
@stop

@push('postScripts')
    <script>
        var $dt       = $('.datatable'),
            DTcolumns = [
                {data: 'id', name: 'id', class: "id-col", "sType": "html"},
                {data: 'media', name: 'media', class: "media-col"},
                {data: 'album', name: 'album', class: "album-col"}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns,
        });
    </script>
@endpush
