<?php

namespace App\Http\Controllers;

use App\Services\DownloadUploadService;

class DownloadUploadController extends Controller
{
    protected $service;

    public function __construct(DownloadUploadService $service)
    {
        $this->service = $service;
    }

    public function downloadAndUpload()
    {
        return response()->json([
            $this->service->downloadAndUpload()
        ]);
    }
}
