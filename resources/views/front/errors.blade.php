{{-- FRONTEND 404 ERROR PAGE --}}

@extends('layouts.front')

@section('title', '404 - Not Found')
@section('content')
    <section class="App-container error">
        <div>
            <h3>{{ $status['title'] }}</h3>
            <hr>
            <p>{{ $status['message'] }}</p>
            <br/>
            <a href="javascript:history.back()">Back</a>
        </div>
    </section>
@stop
