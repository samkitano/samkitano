<div class="row">
    <div class="col-xs-12 Crud-Tools">
        <nav class="navbar Toolbar">
            <div class="Toolbar__Route">
                <ul class="nav">
                    <li role="presentation" class="disabled">
                        <span aria-label="Route Name"
                              class="Toolbar__Route_route hint hint--top-right">{{ $toolbar->info }}</span
                        ><br/>
                        <span aria-label="Controller has methods: {{ $toolbar->controllerMethods }}"
                              class="Toolbar__Route_controller hint hint--bottom-right">{{ $toolbar->controller }}</span
                        >
                    </li>
                </ul>
            </div>

            <div class="Toolbar__Resource-id">
                <ul class="nav">
                    <li class="hint hint--bottom-right"
                        aria-label="Model ID">
                        <span>{{ $toolbar->resourceId }}</span>
                    </li>
                </ul>
            </div>

            <div class="Toolbar__Crud">
                <ul class="nav">
                    @foreach($toolbar->crud as $info)
                        @include('admin.partials.toolbar._crud-toolbar-item', ['info' => $info])
                    @endforeach
                </ul>
            </div>

            <div class="Toolbar__Destroy">
                <ul class="nav">
                    <li role="presentation" style="display:none" class="toolbar_confirm">
                        <form action="" method="post" id="{{ $toolbar->resource }}_del_form">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}

                            <input type="hidden"
                                   name="{{  $toolbar->resource }}_resource_id"
                                   id="{{  $toolbar->resource }}_resource_id">

                            <button class="btn btn-danger btn_toolbar btn_destroy btn-xs"
                                    type="submit">Confirm Destroy</button>
                        </form>
                    </li>

                    <li role="presentation" style="display:none" class="toolbar_cancel">
                        <button class="btn btn-primary btn_toolbar btn_cancel btn-xs" type="button">Cancel</button>
                    </li>
                </ul>
            </div>

            <div class="Toolbar__Relations hint hint--bottom-left" aria-label="Relations">
                @if ($toolbar->relations->hasRelation)
                    <ul class="nav">
                        @foreach($toolbar->relations->models as $relation => $val)
                            <li><a href='{{ route(
                                            'admin::'
                                            . $toolbar->relations->resource
                                            . '.' . $relation
                                            . '.index'
                                            , $toolbar->relations->resourceId) }}'>{{ $relation }}</a></li
                            >
                        @endforeach
                    </ul>
                @endif
            </div>
        </nav>
    </div>
</div>
