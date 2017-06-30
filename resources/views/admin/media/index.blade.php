@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_media') }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Album</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Orientation</th>
                        <th>W</th>
                        <th>H</th>
                        <th>Size</th>
                        <th>Ratio</th>
                        <th>Order</th>
                        <th>Created</th>
                        <th>Updated</th>
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
                {data: 'id', name: 'id', class: 'id-col', 'sType': 'html'},
                {data: 'name', name: 'file', class: 'file-col'},
                {data: 'album', name: 'album', class: 'album-col'},
                {data: 'description', name: 'description', class: 'description-col'},
                {data: 'type', name: 'type', class: 'type-col'},
                {data: 'orientation', name: 'orientation', class: 'orientation-col'},
                {data: 'width', name: 'width', class: 'width-col'},
                {data: 'height', name: 'height', class: 'height-col'},
                {data: 'size', name: 'size', class: 'size-col', 'bSearchable': false},
                {data: 'ratio', name: 'ratio', class: 'ratio-col', 'bSearchable': false},
                {data: 'order', name: 'order', class: 'order-col', 'bSearchable': false},
                {data: 'created_at', name: 'created', class: 'created-col', order: 'desc', type: 'date-euro'},
                {data: 'updated_at', name: 'updated', class: 'updated-col', order: 'desc', type: 'date-euro'}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns
        });
    </script>
@endpush
