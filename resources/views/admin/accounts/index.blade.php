@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        @if (auth()->user()->boss)
            <div class="row">
                <div class="col-xs-12">
                    <table id="dt_admins"
                           class="datatable table table-striped table-condensed table-hover"
                           data-source="{{ route('admin::dt.dt_admins') }}">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Avatar</th>
                            <th>Email</th>
                            <th>Root</th>
                            <th>Since</th>
                            <th>Updated</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xs-12">
                    <table class="datatable table table-striped table-condensed table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Avatar</th>
                            <th>Email</th>
                            <th>Since</th>
                            <th>Updated</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><a class="hint hint--top"
                                   aria-label="Edit"
                                   href="{{ route('admin::accounts.edit', auth()->user()->id) }}"
                                        >{{ auth()->user()->id }}</a
                                ></td>
                            <td><a class="hint hint--top"
                                   aria-label="Show"
                                   href="{{ route('admin::accounts.show', auth()->user()->id) }}"
                                        >{{ auth()->user()->name }}</a></td>
                            @if (auth()->user()->avatar != null)
                                <td><img width="40" src="{{ auth()->user()->avatar }}" alt=""></td>
                            @else
                                <td>---</td>
                            @endif
                            <td>{{ auth()->user()->email }}</td>
                            <td>{{ auth()->user()->created_at }}</td>
                            <td>{{ auth()->user()->updated_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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
                {data: 'boss', name: 'root', class: "root-col"},
                {data: 'created_at', name: 'since', class: "since-col"},
                {data: 'updated_at', name: 'updated', class: "updated-col"}
            ];

        $dt.DataTable({
            ajax: $dt.attr('data-source'),
            columns: DTcolumns,
        });
    </script>
@endPush
