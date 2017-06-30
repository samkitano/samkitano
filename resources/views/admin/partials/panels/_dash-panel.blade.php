<div class="bs-callout bs-callout-default">
    <table class="table table-hover table-bordered table-condensed">
        <thead>
            @if($title === 'Composer')
                <th>Property</th>
                <th>Val</th>
            @elseif($title === 'Dev-dependencies' || $title === 'Dependencies')
                <th colspan="2">Package</th>
            @elseif($title === 'Laravel' || $title === 'Server')
                <th colspan="2">Environment</th>
            @endif
        </thead>

        @foreach($items as $k => $v)
            @if($title === 'Dev-dependencies' || $title === 'Dependencies')
                <tr>
                    <td colspan="2">
                        {{ $k }} <span class="label label-danger">{{ $v }}</span>
                    </td>
                </tr>
            @else
                <tr>
                    <td>{{ $k }}</td>
                    <td>
                        @if(is_bool($v))
                            @if($v)
                                @if($k === 'debug')
                                    <span class="danger glyphicon glyphicon-ok" aria-hidden="true"></span>
                                @else
                                    <span class="success glyphicon glyphicon-ok" aria-hidden="true"></span>
                                @endif
                            @else
                                @if($k === 'debug')
                                    <span class="success glyphicon glyphicon-remove" aria-hidden="true"></span>
                                @else
                                    <span class="danger glyphicon glyphicon-remove" aria-hidden="true"></span>
                                @endif
                            @endif
                        @else
                            {{ $v }}
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
</div>
