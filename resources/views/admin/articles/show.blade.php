@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th colspan="2"><h4>Article</h4></th>
                    </tr>
                    <tr>
                        <th>Attribute</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($article->toArray() as $key => $val)
                            <tr>
                                @if(! is_array($val))
                                    <td>{{ $key }}</td>
                                    @if($key == 'created_at')
                                        <td><span class="label label-primary"
                                                    >{{ $article->created_at->format('d-m-Y') }}</span
                                            > <span class="label label-info"
                                                    >{{ $article->created_at->format('H:i:s') }}</span></td
                                    >
                                    @elseif($key == 'updated_at')
                                        <td><span class="label label-primary"
                                                    >{{ $article->updated_at->format('d-m-Y') }}</span
                                            > <span class="label label-info"
                                                    >{{ $article->updated_at->format('H:i:s') }}</span></td
                                        >
                                    @else
                                        <td>{!! $val !!}</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@stop
