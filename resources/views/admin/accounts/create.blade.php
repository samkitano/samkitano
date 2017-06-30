@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        @if ($isBoss)
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                    <form method="POST" action="{{ route('admin::accounts.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name">Name</label>

                            <input class="form-control"
                                   type="text"
                                   name="name"
                                   id="name"
                                   autofocus
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
                            >

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password (default is 'secret')</label>

                            <input class="form-control"
                                   type="password"
                                   name="password"
                                   id="password"
                                   value="{{ old('password') ? old('password') : 'secret' }}"
                            >

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password_confirmation">Confirm Password</label>

                            <input class="form-control"
                                   type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   value="{{ old('password_confirmation') ? old('password_confirmation') : 'secret' }}"
                            >

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="boss"> Boss
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Create</button>
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