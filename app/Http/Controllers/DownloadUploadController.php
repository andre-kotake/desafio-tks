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

    public function download()
    {
        return response()->json([
            $this->service->download()
        ]);
    }
}
