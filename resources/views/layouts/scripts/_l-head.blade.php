{{-- FRONTEND CSRF TOKEN FOR JavaScript --}}

<script>
    window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
</script>
