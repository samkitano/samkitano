@extends('layouts.admin')

@section('content')
    <section class="resources">
        <div class="separator"></div>

        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3">
                <form action="{{ route('admin::change-password') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group{{ $errors->has('old_password') ? ' has-error' : '' }}">
                        <label for="old_password">Old Password</label>
                        <input type="password"
                               class="form-control"
                               name="old_password"
                               autofocus
                               id="old_password">

                        @if ($errors->has('old_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('old_password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">New Password</label>
                        <input type="password"
                               class="form-control"
                               name="password"
                               id="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password"
                               class="form-control"
                               name="password_confirmation"
                               id="password_confirmation">

                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@stop
