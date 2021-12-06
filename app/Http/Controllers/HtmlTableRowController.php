<?php

namespace App\Http\Controllers;

use App\Services\HtmlTableRowService;

class HtmlTableRowController extends Controller
{
    protected $service;

    public function __construct(HtmlTableRowService $service)
    {
        $this->service = $service;
    }

    public function saveTableToDatabase()
    {
        return response()->json([
            $this->service->saveTableToDatabase()
        ]);
    }
}
