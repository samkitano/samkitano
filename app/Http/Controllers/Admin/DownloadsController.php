<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Http\Controllers\AdminController;

class DownloadsController extends AdminController
{
    /**
     * @param string $file
     *
     * @return $this
     */
    public function __invoke($file)
    {
        $f = base64_decode($file);

        Log::info('Downloaded file: '. $f);

        return response()->download($f)->deleteFileAfterSend(true);
    }
}
