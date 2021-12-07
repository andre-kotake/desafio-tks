<?php

namespace App\Http\Controllers;

use App\Services\HtmlFormService;

class HtmlFormController extends Controller
{
    protected $service;

    function __construct(HtmlFormService $service)
    {
        $this->service = $service;
    }

    function fillForm()
    {
        return response()->json([
            $this->service->fillFormAndSubmit()
        ]);
    }
}
