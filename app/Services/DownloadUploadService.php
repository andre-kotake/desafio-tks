<?php

namespace App\Services;

use App\Pages\DownloadPage;

class DownloadUploadService
{
    function download()
    {
        return DownloadPage::get()->download();
    }
}
