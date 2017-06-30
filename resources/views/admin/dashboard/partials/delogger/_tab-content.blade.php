<div class="tab-content" id="Log_contents">
    @foreach ($logs as $log)
        @include ('admin.dashboard.partials.delogger._tab-panel', ['loop' => $loop, 'log' => $log])
    @endforeach
</div>
