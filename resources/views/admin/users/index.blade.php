@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_users"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_users') }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Avatar</th>
                        <th>Email</th>
                        <th>Slug</th>
                        <th><span class="hint hint--top" aria-label="Comments">{!! ICON_COMMENT !!}</span></th>
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
                {data: 'id', name: 'id', class: "id-col", "sType": "html"},
                {data: 'name', name: 'name', class: "name-col"},
                {data: 'avatar', name: 'avatar', class: "avatar-col"},
                {data: 'email', name: 'email', class: "email-col"},
                {data: 'slug', name: 'slug', class: "slug-col"},
                {data: 'comments', name: 'comments', class: "comments-col", "bSearchable": false},
                {data: 'created_at', name: 'created', class: "created-col", order: "desc", type: "date-euro"},
                {data: 'updated_at', name: 'updated', class: "updated-col", order: "desc", type: "date-euro"}
            ];

        $dt.DataTable({
            ajax      : $dt.attr('data-source'),
            columns   : DTcolumns,
        });
    </script>
@endpush
