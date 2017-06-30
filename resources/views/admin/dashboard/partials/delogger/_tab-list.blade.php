{{-- PARENT: _delogger --}}

<ul class="nav nav-tabs" role="tablist" id="Log_files">
    @foreach ($logs as $log)
        <li class="{{ $loop->first ? 'active' : '' }}"
            ><a href="#{{ $log['file']['slug'] }}"
                aria-controls="{{ $log['file']['slug'] }}"
                role="tab"
                data-toggle="tab">{{ $log['file']['name'] }} <span class="{{ $log['file']['is_big']
                                                                ? 'text-danger' : '' }}"></span
                                                             >({{ $log['file']['bytes'] }})</a></li
        >
    @endforeach
</ul>
