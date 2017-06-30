{{-- FRONTEND USER PROFILE (VUE) --}}

@extends('layouts.front')

@section('title', 'Profiles')
@section('section_name', 'Users')
@section('content')
    <app-user owned="{{ json_encode($own) }}"
              user="{{ json_encode(auth()->check() ? $user : false) }}"></app-user>
@stop

@push('postScripts')
    <script src="/js/user.js"></script>
@endpush
