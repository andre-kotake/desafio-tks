<?php

namespace App\Services;

use App\Pages\DownloadPage;
use App\Pages\UploadPage;

class DownloadUploadService
{
    function downloadAndUpload()
    {
        $filePath = $this->download();
        return $this->upload($filePath);
    }

    private function download()
    {
        return DownloadPage::get()->download();
    }

    private function upload($filePath)
    {
        return UploadPage::get()
            ->upload($filePath)
            ->getUploadedFilename();
    }
}
