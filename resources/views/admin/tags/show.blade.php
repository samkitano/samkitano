@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Tag</h4></th>
                    </tr>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($tag->toArray() as $key => $val)
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
