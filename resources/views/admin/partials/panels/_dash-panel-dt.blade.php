<div class="bs-callout bs-callout-default">
    <table id="dt_vendor" class="table table-hover table-bordered table-condensed">
        <thead>
        <tr>
            <th>Package</th>
            <th>Dependencies</th>
        </tr>
        </thead>

        <tbody>
            @foreach($items as $package)
                <tr>
                    <td>{{ $package['name'] }} <span class="label label-danger">{{ $package['version'] }}</span></td>

                    <td>
                        <ul>
                            @if(is_array($package['production']))
                                <li role="separator" class="divider"><h4>Production</h4></li>

                                @foreach($package['production'] as $dependencyName => $dependencyVersion)
                                    <li>{{ $dependencyName }} <span class="label label-danger">{{ $dependencyVersion }}</span></li>
                                @endforeach
                            @else
                                <li><span class="label label-primary">{{ $package['production'] }}</span></li>
                            @endif

                            @if(is_array($package['development']))
                                <li role="separator" class="divider"><h4>Development</h4></li>

                                @foreach($package['development'] as $dependencyName => $dependencyVersion)
                                    <li>{{ $dependencyName }} <span class="label label-danger">{{ $dependencyVersion }}</span></li>
                                @endforeach
                            @else
                                <li><span class="label label-primary">{{ $package['development'] }}</span></li>
                            @endif
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>