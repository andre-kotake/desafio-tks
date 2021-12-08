<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;

class PdfToCsvService
{

    function convert()
    {
        $file = storage_path('app/files') . '/LeituraPDF.pdf';
        $text = (new Pdf())
            ->setPdf($file)
            ->text();
    }
}
