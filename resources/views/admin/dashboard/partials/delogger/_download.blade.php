{{-- PARENT: _tab-panel --}}

<div class="pull-left">
    <form method="POST" action="{{ route('admin::delogger.download', $link) }}">
        {{ csrf_field() }}

        <button type="submit"
                class="btn btn-primary">Download</button>
    </form>
</div>
