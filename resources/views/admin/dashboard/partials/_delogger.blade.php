<div class="col-xs-12">
    <div class="panel panel-primary">
        <div id="delogger" class="panel-heading panel-collapsed clickable">
            <h3 class="panel-title">Logs</h3>
            <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
        </div>

        @if (count($logs))
            <div class="panel-body" style="display:none;">
                @include('admin.dashboard.partials.delogger._tab-list', ['logs' => $logs])

                @include('admin.dashboard.partials.delogger._tab-content', ['logs' => $logs])
            </div>
        @else
            <div class="panel-body" style="display:none;">
                <h2 class="text-info"><i class="fa fa-info-circle"></i> No logs found</h2>
            </div>
        @endif
    </div>
</div>
