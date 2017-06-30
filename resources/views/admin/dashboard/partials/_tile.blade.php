<div class="col-sm-{{ $size }}">
    <div class="Tile Tile_{{ $color }}">
        <h4 class="Tile__Title">{{ $title }}</h4>

        <a class="Tile_link {{ $href === '#' ? 'no-action' : '' }}"
           href="{{ $href }}"><span class="glyphicon glyphicon-play-circle"></span></a>

        <div class="clearfix"></div>

        <hr>

        <small>
            <p>{{ $p1 }}</p>
            <p>{{ $p2 }}</p>
        </small>
    </div>
</div>
