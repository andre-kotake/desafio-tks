<?php

namespace App\Services;

use App\Pages\HtmlDownloadPage;

class HtmlDownloadUploadService
{
    function download()
    {
        return HtmlDownloadPage::get()->download();
    }
}
