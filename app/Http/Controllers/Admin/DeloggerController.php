<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Kitano\Zipper\Zipper;
use App\Kitano\Delogger\Delogger;
use App\Http\Controllers\AdminController;

class DeloggerController extends AdminController
{
    /** @var \App\Kitano\Delogger\Delogger */
    protected $delogger;

    /** @var \App\Kitano\Zipper\Zipper */
    protected $zipper;


    /**
     * @param \App\Kitano\Delogger\Delogger $delogger
     * @param \App\Kitano\Zipper\Zipper     $zipper
     * @param \Illuminate\Http\Request      $request
     */
    public function __construct(Delogger $delogger, Zipper $zipper, Request $request)
    {
        parent::__construct($request);
        parent::setResource('Log');

        $this->delogger = $delogger;
        $this->zipper = $zipper;
    }

    /**
     * Archive a log file
     *
     * @param   string $filePath
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive($filePath)
    {
        $archived = $this->delogger->archive($filePath);
        $status = ! $archived
            ? $this->failRenameFile(basename(base64_decode($filePath)))
            : $this->successRenameFile(basename(base64_decode($filePath)), $archived);

        return redirect()
            ->route('admin::dashboard')
            ->with($status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   string  $filePath
     * @return \Illuminate\Http\Response
     */
    public function destroy($filePath)
    {
        $deleted = $this->delogger->destroy($filePath);
        $status = ! $deleted ? $this->failFileUnlinkStatus($deleted) : $this->successDestroyStatus($deleted);

        return redirect()
            ->route('admin::dashboard')
            ->with($status);
    }

    /**
     * Download Log file
     *
     * @param   string $filePath
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($filePath)
    {
        $file = base64_decode($filePath);
        $zipped = $this->zipper->compress($file);

        if (! $zipped) {
            return back()->with($this->failDownloadStatus('Compression failed'));
        }

        return back()->with(
            'file.download',
            base64_encode(public_path('downloads') . DIRECTORY_SEPARATOR . basename($file) . '.zip')
        );
    }
}
