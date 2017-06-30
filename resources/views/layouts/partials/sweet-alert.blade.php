{{-- SWEET ALERT 2 INTEGRATION --}}

@php
    $status = session('status') ? session('status') : false;
    $swal   = false;
    $timer  = $status['type'] == 'error' ? null : 3000;

    if ($status) {
        $swal = json_encode([
                'title'   => $status['title'],
                'message' => $status['message'],
                'type'    => $status['type'],
                'auto'    => $timer,
                'confirm' => isset($status['confirm']) ? $status['confirm'] : 'OK',
        ]);
    }
@endphp

<script>
    var sw = JSON.parse(<?php echo json_encode([$swal]); ?>);

    if (sw) {
        swal({
            title: sw.title,
            html : sw.message,
            type : sw.type,
            timer: sw.auto ? 3000 : null,
            confirmButtonText: sw.confirm
        }).catch(swal.noop);
    }
</script>
