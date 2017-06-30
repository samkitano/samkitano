@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_article_comments', $article->id) }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Article</th>
                        <th>User</th>
                        <th>Body</th>
                        <th><span class="hint hint--top" aria-label="Likes"
                                    >{{ \App\Http\Controllers\Admin\DatatablesController::HEART }}</span></th
                        >
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
                {data: 'title', name: 'article', class: "article-col"},
                {data: 'user', name: 'user', class: "user-col"},
                {data: 'body', name: 'body', class: "body-col"},
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
