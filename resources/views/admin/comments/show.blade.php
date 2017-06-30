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
                                @if ($key === 'body')
                                    <td>
                                        <p>
                                            <strong>Raw</strong><br/>
                                            {!! $val !!}
                                        </p>
                                        <hr>
                                        <strong>Markdown</strong><br/>
                                        <div id="markdown"></div>
                                    </td>
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
        <input type="hidden" id="mkd_input" value="{{ $comment->body }}">
    </section>
@stop

@push('postScripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.3.1/markdown-it.min.js"></script>

    <script>
        var md = window.markdownit();
        var body = document.getElementById('mkd_input').value;
        var result = md.render(body);

        document.getElementById('markdown').innerHTML = result;
    </script>
@endPush
