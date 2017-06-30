@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Comment</h4></th>
                    </tr>
                    <tr>
                        <th>Key</th>
                        <th>Val</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comment->toArray() as $key => $val)
                        <tr>
                            @if(! is_array($val))
                                <td>{{ $key }}</td>
                                <td>{!! $val !!}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop
