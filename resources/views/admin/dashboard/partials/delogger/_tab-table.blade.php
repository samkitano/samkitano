{{-- PARENT: _tab-panel --}}

<div class="bs-callout bs-callout-default">
    <table id="dt_log{{ $loopIdx }}" class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th>Level</th>
                <th>Context</th>
                <th>Date</th>
                <th>Error</th>
            </tr>
        </thead>

        <tbody>
            @foreach($log as $entry)
                <tr class="searchable" data-display="stack{{ $loop->index }}">
                    <td class="text-{{ $entry['class'] }}">
                        <i class="{{ $entry['img'] }}"
                              aria-hidden="true"></i
                        >&nbsp;{{ $entry['level'] }}
                    </td>
    
                    <td class="text">{{ $entry['context'] }}</td>
    
                    <td class="date">{{ $entry['date'] }}</td>
    
                    <td class="text">
                        @if ($entry['stack'])<a class="pull-right expand btn btn-default btn-xs hint hint--left"
                                                aria-label="Stack Trace"
                                                data-display="stack{{ $loop->index }}"><i class="fa fa-caret-down"></i></a
                        >@endif{{ $entry['message'] }}@if (isset($entry['in']))<br
                            >{{ $entry['in'] }}@endif @if ($entry['stack'])<div class="stack"
                                                                                id="stack{{ $loop->index }}"
                                    >{{ trim($entry['stack']) }}</div
                            >
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
