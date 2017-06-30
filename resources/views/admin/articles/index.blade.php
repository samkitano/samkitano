@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table id="dt_articles"
                       class="datatable table table-condensed table-hover"
                       data-source="{{ route('admin::dt.dt_articles') }}">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Subtitle</th>
                        <th><span class="hint hint--top" aria-label="Published"
                                    >{!! ICON_FLAG_BLACK !!}</span></th
                        >
                        <th><span class="hint hint--top" aria-label="Comments"
                                    >{!! ICON_COMMENT !!}</span></th
                        >
                        <th><span class="hint hint--top" aria-label="Likes"
                                    >{!! ICON_HEART !!}</span></th
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
                {data: 'id', name: 'id', class: 'id-col', 'sType': 'html'},
                {data: 'title', name: 'title', class: 'title-col'},
                {data: 'subtitle', name: 'subtitle', class: 'subtitle-col'},
                {data: 'published', name: 'published', class: 'published-col'},
                {data: 'comments', name: 'comments', class: 'comments-col', 'bSearchable': false},
                {data: 'likes', name: 'likes', class: 'likes-col', 'bSearchable': false},
                {data: 'created_at', name: 'created', class: 'created-col', order: 'desc', type: 'date-euro'},
                {data: 'updated_at', name: 'updated', class: 'updated-col', order: 'desc', type: 'date-euro'}
        ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns
        });
    </script>
@endpush
