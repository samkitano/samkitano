@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        @if ($isBoss || auth('admin')->user()->id === $account->id)
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <form method="POST" action="{{ route('admin::accounts.update', $account->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name</label>

                            <input class="form-control"
                                   type="text"
                                   name="name"
                                   id="name"
                                   autofocus
                                   value="{{ old('name') ? old('name') : $account->name }}"
                            >

                            @if ($errors->has('name'))
                                <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email">Email</label>

                            <input class="form-control"
                                   type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') ? old('email') : $account->email }}"
                            >

                            @if ($errors->has('email'))
                                <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i
                        > Sorry, you don't have enough privileges to access this page.</h3
            >
        @endif
    </section>
@stop
