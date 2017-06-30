@extends('layouts.front')

@section('title', 'Reset-Password')
@section('section_name', 'Reset-Password')

@section('content')
    <h1 class="Pg-header">Reset Your Password</h1>

    <form method="POST" action="{{ route('password.request') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="H-Form" role="form">
            <div class="H-Form__Heading">
                <span>Reset Password</span>
            </div>

            <div class="H-Form__Body">
                <div class="H-Form__Item">
                    <h3>All fields are required</h3>
                </div>

                <div class="H-Form__Item">
                    <label for="email" class="Form__Group__Label">E-Mail Address<sup>*</sup></label>

                    <input id="email"
                           type="email"
                           class="Field{{ $errors->has('email') ? ' error' : '' }}"
                           name="email"
                           value="{{ $email or old('email') }}"
                           required
                           autofocus>
                </div>

                <div class="H-Form__Item">
                    <label for="password" class="Form__Group__Label">New Password<sup>*</sup></label>

                    <input id="password"
                           type="password"
                           class="Field{{ $errors->has('password') ? ' error' : '' }}"
                           name="password"
                           required>
                </div>

                <div class="H-Form__Item">
                    <label for="password-confirm" class="Form__Group__Label">Confirm New Password<sup>*</sup></label>

                    <input id="password-confirm"
                           type="password"
                           class="Field{{ $errors->has('password_confirmation') ? ' error' : '' }}"
                           name="password_confirmation"
                           required>
                </div>

                <div class="H-Form__Item__error">
                    <span class="Form-error">
                        @if ($errors->has('email'))
                            {{ $errors->first('email') }} <br/>
                        @elseif ($errors->has('password'))
                            {{ $errors->first('password') }} <br/>
                        @elseif ($errors->has('password_confirmation'))
                            {{ $errors->first('password_confirmation') }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="H-Form__Footer">
                <div class="H-Form__Item H-Form__Button">
                    <button type="submit" class="Button Button_default">
                        Reset Password
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
