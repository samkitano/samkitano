@extends('layouts.admin')

@section('content')
    <section class="resources">
        @include('admin.partials.toolbar.crud-toolbar')

        @if ($isBoss)
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2"><h4>Admin</h4></th>
                        </tr>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($account->toArray() as $key => $val)
                            <tr>
                                @if(! is_array($val))
                                    <td>{{ $key }}</td>
                                    @if ($key == 'avatar' && isset($account->avatar))
                                        <td><img width="80px" src="{{ $account->avatar }}" alt=""></td>
                                    @elseif ($key == 'created_at')
                                        <td><span class="label label-primary"
                                                    >{{ $account->created_at->format('d-m-Y') }}</span
                                                    > <span class="label label-info"
                                                    >{{ $account->created_at->format('H:i:s') }}</span></td
                                        >
                                    @elseif($key == 'updated_at')
                                        <td><span class="label label-primary"
                                                    >{{ $account->updated_at->format('d-m-Y') }}</span
                                                    > <span class="label label-info"
                                                    >{{ $account->updated_at->format('H:i:s') }}</span></td
                                        >
                                    @elseif ($key == 'active')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @elseif ($key == 'boss')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @elseif ($key == 'admin')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
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
        @else
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2"><h4>Admin</h4></th>
                        </tr>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($account->toArray() as $key => $val)
                            @if ($key != 'boss')
                            <tr>
                                @if(! is_array($val))
                                    <td>{{ $key }}</td>
                                    @if ($key == 'avatar')
                                        @if ($account->avatar != null)
                                            <td><img width="80px" src="{{ $account->avatar }}" alt=""></td>
                                        @else
                                            <td>---</td>
                                        @endif
                                    @elseif ($key == 'created_at')
                                        <td><span class="label label-primary"
                                                    >{{ $account->created_at->format('d-m-Y') }}</span
                                                    > <span class="label label-info"
                                                    >{{ $account->created_at->format('H:i:s') }}</span></td
                                                >
                                    @elseif($key == 'updated_at')
                                        <td><span class="label label-primary"
                                                    >{{ $account->updated_at->format('d-m-Y') }}</span
                                                    > <span class="label label-info"
                                                    >{{ $account->updated_at->format('H:i:s') }}</span></td
                                                >
                                    @elseif ($key == 'active')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @elseif ($key == 'boss')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @elseif ($key == 'admin')
                                        <td>{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @else
                                        <td>{!! $val !!}</td>
                                    @endif
                                @endif
                            </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </section>
@stop