@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-striped table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_article_likes', $article->id) }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Article</th>
                        <th>Published</th>
                        <th>User</th>
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
                {data: 'created_at', name: 'published', class: "created-col", order: "desc", type: "date-euro"},
                {data: 'user', name: 'user', class: "user-col"}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns,
        });
    </script>
@endpush
