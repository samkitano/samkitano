<?php

namespace App\Http\Controllers\Admin;

use App\Album;
use App\Http\Controllers\AdminController;
use App\Kitano\MediaManager\Manager;

class ToolsController extends AdminController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function clearCache()
    {
        cache()->flush();

        return response()->json(['message' => 'Cache Cleared! You should probably refresh this page.'], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAlbums()
    {
        $payload['albums'] = Album::with(['media' => function ($q) {
            $q->whereType('image');
        }])->get();

        $payload['media_path'] = url(Manager::$mediaFolder);

        return response()->json($payload, 200);
    }
}
