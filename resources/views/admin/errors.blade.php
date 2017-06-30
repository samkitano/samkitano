@extends('layouts.admin')

@section('content')
    <section class="resources error">
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-danger text-center">
                    <h3>{{ $status['title'] }}</h3>
                    <hr>
                    <p>{{ $status['message'] }}</p>
                    <br/>
                    <a href="javascript:history.back()">Back</a>
                </div>
            </div>
        </div>
    </section>
@stop
