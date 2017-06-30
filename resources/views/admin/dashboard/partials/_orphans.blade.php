<div class="col-xs-12">
    <div class="panel panel-primary">
        <div id="orphans" class="panel-heading panel-collapsed clickable">
            <h3 class="panel-title">Orphan Media Files ({{ count($orphans) }})</h3>
            <span class="pull-right"><i class="glyphicon glyphicon-chevron-down"></i></span>
        </div>

        <div class="panel-body" style="display:none">
            <div class="bs-callout bs-callout-default">
                <table id="dt_orphans" class="table table-hover table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>File</th>
                        <th>Size</th>
                        <th>Img</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($orphans as $orphan)
                            <tr>
                                <td>{{ $orphan }}</td>
                                <td>{{ formatBytes(filesize(public_path($orphan))) }}</td>
                                <td>@if(exif_imagetype(public_path($orphan)))
                                        <img width="50" src="{{ url($orphan) }}" alt="">
                                    @else
                                        <i class="fa fa-2x fa-file-o"></i>
                                    @endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="row">
                <div class="col-xs-12">
                    <form method="POST" action="{{ route('admin::unlink-orphans') }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-danger pull-left">Delete Orphans</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
