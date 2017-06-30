@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_user_comments"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ isset($user)
                                        ? route('admin::dt.dt_user_comments', $user->id)
                                        : route('admin::dt.dt_comments') }}"
                >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Article</th>
                        <th>Body</th>
                        <th><span class="hint hint--top" aria-label="Likes">{!! ICON_HEART !!}</span></th>
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
                {data: 'user', name: 'user', class: "user-col"},
                {data: 'article', name: 'article', class: "article-col"},
                {data: 'body', name: 'body', class: "comment-col"},
                {data: 'likes', name: 'likes', class: "likes-col"},
                {data: 'created_at', name: 'created', class: "created-col", order: "desc", type: "date-euro"},
                {data: 'updated_at', name: 'updated', class: "updated-col", order: "desc", type: "date-euro"}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns,
        });
    </script>
@endpush
