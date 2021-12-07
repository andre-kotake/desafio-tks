<?php

namespace App\Http\Controllers;

use App\Services\HtmlDownloadUploadService;

class HtmlDownloadUploadController extends Controller
{
    protected $service;

    public function __construct(HtmlDownloadUploadService $service)
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
