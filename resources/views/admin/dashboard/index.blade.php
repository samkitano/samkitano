@extends('layouts.admin')

@section('content')
    <br/>

    <div class="row">
        @include('admin.dashboard.partials._tile',
            [
                'size' => '3',
                'color' => 'blue',
                'title' => "{$users['today']} Users",
                'href' => 'admin/users',
                'p1' => "{$users['yesterday']} Yesterday",
                'p2' => $users['today'] - $users['yesterday'] . ' Today'
            ]
        )

        @include('admin.dashboard.partials._tile',
            [
                'size' => '3',
                'color' => 'blue',
                'title' => $articles['published'] + $articles['unpublished'] . " Articles",
                'href' => 'admin/articles',
                'p1' => "{$articles['published']} Published",
                'p2' => $articles['unpublished'] . " Unpublished"
            ]
        )

        @include('admin.dashboard.partials._tile',
            [
                'size' => '3',
                'color' => 'blue',
                'title' => $messages['total'] . " Messages",
                'href' => 'admin/contacts',
                'p1' => "{$messages['read']} Read",
                'p2' => $messages['total'] - $messages['read'] . " Unread"
            ]
        )

        @include('admin.dashboard.partials._tile',
            [
                'size' => '3',
                'color' => $logErrors > 0 ? 'red' : 'blue',
                'title' => "Log Errors",
                'href' => '#',
                'p1' => "{$logErrors} Current",
                'p2' => "0 Ideal"
            ]
        )
    </div>

    <div class="row">
        @include('admin.dashboard.partials._sys')
    </div>

    <div class="row">
        @include('admin.dashboard.partials._delogger')
    </div>

    <div class="row">
        @include('admin.dashboard.partials._orphans')
    </div>
@stop

@push('postScripts')
    <script src="js/dashboard.js"></script>
@endpush
