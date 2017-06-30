@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('admin::users.update', $user->id) }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="_method" value="PATCH">

                    {{--NAME--}}
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Name</label>
                                <input class="form-control"
                                       type="text"
                                       name="name"
                                       id="name"
                                       autofocus
                                       value="{{ old('name') ? old('name') : $user->name }}"
                                >

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--EMAIL--}}
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email">Email</label>
                                <input class="form-control"
                                       type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email') ? old('email') : $user->email }}"
                                >

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--SLUG--}}
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                <label for="slug">Slug</label>
                                <input class="form-control"
                                       type="text"
                                       name="slug"
                                       id="slug"
                                       value="{{ old('slug') ? old('slug') : $user->slug }}"
                                >

                                @if ($errors->has('slug'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('slug') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{--BIO--}}
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                                <label for="bio">Bio</label>
                                <input class="form-control"
                                       type="text"
                                       name="bio"
                                       id="bio"
                                       value="{{ old('bio') ? old('bio') : $user->profile->bio }}"
                                >

                                @if ($errors->has('bio'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('bio') }}</strong>
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
