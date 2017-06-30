@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::tags.update', $tag->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Name</label>
                                <input class="form-control"
                                       type="text"
                                       name="name"
                                       id="name"
                                       autofocus
                                       value="{{ old('name') ? old('name') : $tag->name }}"
                                >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
