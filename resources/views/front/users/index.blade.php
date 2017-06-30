{{-- FRONTEND USERS PAGE (restricted to admins) --}}

@extends('layouts.front')

@section('title', 'Users')
@section('section_name', 'Users')
@section('content')
    @if ($users)
        <h1 class="Pg-header">Users <span>({{ count($users) }})</span></h1>

        @if (count($users))
            <ul>
                @foreach($users as $user)
                    <li><a href="{{ route('front::users.show', $user->slug) }}">{{ $user->name }}</a></li>
                @endforeach
            </ul>
        @else
            <h2>No users found.</h2>
        @endif
    @else
        <h3 class="error">Sorry, you can not access this page.</h3>
    @endif
@stop
