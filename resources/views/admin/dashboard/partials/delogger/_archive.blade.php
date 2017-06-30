{{-- PARENT: _tab-panel --}}

<form method="POST" action="{{ route('admin::delogger.archive', $link) }}">
    {{ csrf_field() }}

    <button style="margin-right:20px;" type="submit" class="btn btn-primary pull-left">Archive {{ $name }}</button>
</form>
