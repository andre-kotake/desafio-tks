<?php

namespace App\Http\Controllers;

use App\Services\PdfToCsvService;

class PdfToCsvController extends Controller
{
    protected $service;

    public function __construct(PdfToCsvService $service)
    {
        $this->service = $service;
    }

    public function convert()
    {
        return response()->json([
            $this->service->convert()
        ]);
    }
}
