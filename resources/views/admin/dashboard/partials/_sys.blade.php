<div class="col-xs-12">
    <div class="panel panel-primary">
        <div id="sys" class="panel-heading panel-collapsed clickable">
            <h3 class="panel-title">System</h3>
            <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
        </div>

        <div class="panel-body" style="display:none">
            <ul class="nav nav-tabs" role="tablist">
                @foreach($envInfo as $key => $val)
                    <li class="{{ $loop->first ? 'active' : '' }}"
                        ><a href="#{{ $key }}"
                            aria-controls="{{ $key }}"
                            role="tab"
                            data-toggle="tab">{{ ucfirst($key) }}</a></li
                    >
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach($envInfo as $key => $val)
                    <div role="tabpanel"
                         class="tab-pane {{ $loop->first ? 'active' : '' }}"
                         id="{{ $key }}"
                    >
                        @if($key === 'vendor')
                            @include(
                                'admin.partials.panels._dash-panel-dt',
                                [
                                    'title' => ucfirst($key),
                                    'items' => $val,
                                ]
                            )
                        @else
                            @include(
                                'admin.partials.panels._dash-panel',
                                [
                                    'title' => ucfirst($key),
                                    'items' => $val,
                                ]
                            )
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
