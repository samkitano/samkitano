@php
    $albums = getAllAlbums();
@endphp

@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        @if($albums->count())
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ route('admin::media.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="form-group{{ $errors->has('album_id') ? ' has-error' : '' }}">
                                    <label for="album_id">Album</label>
                                    <select name="album_id"
                                            id="album_id"
                                            class="select-album form-control"
                                            style="width:100%!important;">
                                        @foreach($albums as $album)
                                            <option value="{{ $album->id }}"
                                                    @if(old('album_id'))
                                                        @if($album->id == old('album_id'))
                                                            selected="selected"
                                                        @endif
                                                    @endif
                                            >{{ $album->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('album'))
                                    <span class="help-block"><strong>{{ $errors->first('album') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Description</label>
                                    <input class="form-control"
                                           type="text"
                                           name="description"
                                           id="description"
                                           autofocus
                                           value="{{ old('description') }}"
                                    >
                                </div>

                                @if ($errors->has('description'))
                                    <span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img data-src="holder.js/100%x100%" alt="">
                                    </div>

                                    <div class="fileinput-preview fileinput-exists thumbnail"
                                         style="max-width: 200px; max-height: 150px;"></div
                                    >

                                    <div>
                                        <span class="btn btn-default btn-file"
                                            ><span class="fileinput-new">Select media</span
                                            ><span class="fileinput-exists">Change</span
                                                ><input type="file" name="media"></span
                                            >

                                        <a href="#"
                                           class="btn btn-default fileinput-exists"
                                           data-dismiss="fileinput">Remove</a
                                        >
                                    </div>

                                    @if ($errors->has('media'))
                                        <span class="help-block"><strong>{{ $errors->first('media') }}</strong></span>
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
        @else
            <a href="/admin/albums/create"
                    ><h2 class="text-info"><i class="fa fa-info-circle"></i> Please, create an album first.</h2></a
            >
        @endif
    </section>
@stop
