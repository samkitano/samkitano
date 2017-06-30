{{-- PARENT: _tab-content --}}

<div role="tabpanel"
     class="tab-pane {{ $loop->first ? 'active' : '' }}"
     id="{{ $log['file']['slug'] }}"
>
    @if (is_null($log['log']))
        <h4 class="text-info"><span class="glyphicon glyphicon-info-sign"
                                    aria-hidden="true">&nbsp;</span
                               >Log size exceeds 20 MB. ({{ $log['file']['bytes'] }}) Please Download</h4
        >
    @else
        @include (
            'admin.dashboard.partials.delogger._tab-table',
            ['loopIdx' => $loop->index, 'log' => $log['log'], 'link' => $log['file']['link']]
        )
    @endif

    <div class="clearfix"></div>

    <div class="row Log-Tools">
        <div class="col-xs-12">
            @include (
                'admin.dashboard.partials.delogger._destroy',
                ['link' => $log['file']['link'], 'name' => $log['file']['name']]
            )

            @if ($log['file']['name'] == 'laravel.log')
                @include (
                    'admin.dashboard.partials.delogger._archive',
                    ['link' => $log['file']['link'], 'name' => $log['file']['name']]
                )
            @endif

            @include (
                'admin.dashboard.partials.delogger._download',
                ['link' => $log['file']['link'], 'file' => $log['file']['path']]
            )
        </div>
    </div>
</div>
