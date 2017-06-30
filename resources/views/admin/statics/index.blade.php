@extends('layouts.admin')

@section('content')
    <section class="resources">
        @if ($isBoss)
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_statics') }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>Created</th>
                        <th>Updated</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        @else
            <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i
                        > Sorry, you don't have enough privileges to access this page.</h3
            >
        @endif
    </section>
@stop

@push('postScripts')
    <script>
        var $dt       = $('.datatable'),
            DTcolumns = [
                {data: 'id', name: 'id', class: 'id-col', 'sType': 'html'},
                {data: 'slug', name: 'slug', class: 'slug-col'},
                {data: 'title', name: 'subtitle', class: 'title-col'},
                {data: 'created_at', name: 'created', class: 'created-col', order: 'desc', type: 'date-euro'},
                {data: 'updated_at', name: 'updated', class: 'updated-col', order: 'desc', type: 'date-euro'}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns
        });
    </script>
@endpush
