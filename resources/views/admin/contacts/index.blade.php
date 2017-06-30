@extends('layouts.admin')

@section('content')
    <section class="resources">
        @if ($isBoss)
            @include('admin.partials.toolbar.crud-toolbar')

            <div class="row">
                <div class="col-xs-12">
                    <table id="dt_articles"
                           class="datatable table table-striped table-condensed table-hover"
                           data-source="{{ route('admin::dt.dt_contacts') }}">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>IP</th>
                            <th>Date</th>
                            <th>Read</th>
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
                {data: 'name', name: 'name', class: 'name-col'},
                {data: 'email', name: 'email', class: 'email-col'},
                {data: 'message', name: 'message', class: 'message-col'},
                {data: 'ip', name: 'ip', class: 'ip-col'},
                {data: 'created_at', name: 'date', class: 'date-col'},
                {data: 'read', name: 'read', class: 'read-col', 'bSearchable': false}
            ];

        $dt.DataTable({
            order: [[ 5, "desc" ]],
            ajax: $dt.attr('data-source'),
            columns: DTcolumns
        });
    </script>
@endpush
