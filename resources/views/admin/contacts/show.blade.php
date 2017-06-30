@extends('layouts.admin')

@section('content')
    <section class="resources">
        @if ($isBoss)
            @include('admin.partials.toolbar.crud-toolbar')

            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th colspan="2"><h4>Contact</h4></th>
                        </tr>
                        <tr>
                            <th>Attribute</th>
                            <th>Value</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contact->toArray() as $key => $val)
                            <tr>
                                @if(! is_array($val))
                                    <td>{{ $key }}</td>
                                    @if($key == 'created_at')
                                        <td><span class="label label-primary"
                                                    >{{ $contact->created_at->format('d-m-Y') }}</span
                                            > <span class="label label-info"
                                                    >{{ $contact->created_at->format('H:i:s') }}</span></td
                                        >
                                    @elseif($key == 'updated_at')
                                        <td><span class="label label-primary"
                                                    >{{ $contact->updated_at->format('d-m-Y') }}</span
                                            > <span class="label label-info"
                                                    >{{ $contact->updated_at->format('H:i:s') }}</span></td
                                        >
                                    @elseif($key == 'read')
                                        <td id="read">{!! $val ? ICON_CHECK_MARK_GREEN : ICON_X_MARK_RED !!}</td>
                                    @else
                                        <td>{{ $val }}</td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i
                        > Sorry, you don't have enough privileges to access this page.</h3
            >
        @endif
    </section>
@stop

@push('postScripts')
    @if (! $contact->read)
        <script>
            $(document).ready(function () {

                setTimeout( function () {
                    $.post("admin/mark-contact-as-read", { id: "{{ $contact->id }}", "_method": "PATCH" })
                     .done(function (data) {
                         $("#read").html(data);
                     });
                }, 3000);
            });
        </script>
    @endif
@endpush
