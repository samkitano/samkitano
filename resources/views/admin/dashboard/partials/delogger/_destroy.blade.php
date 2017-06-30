{{-- PARENT: _tab-panel --}}

<form method="POST" action="{{ route('admin::delogger.destroy', $link) }}">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <button type="submit" class="btn btn-danger pull-right">Destroy {{ $name }}</button>
</form>
