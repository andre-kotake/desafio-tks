<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/file-upload-test.html';
    }

    function download()
    {
        $contents = file_get_contents('https://testpages.herokuapp.com' . $this->findElement('//a[@id="direct-download-a"]')->getAttribute('href'));
        Storage::disk('files')->put('Teste TKS.txt', $contents);

        while (!Storage::disk('files')->exists('Teste TKS.txt')) {
            sleep(1);
        }

        return storage_path('app/files') . '/Teste TKS.txt';
    }
}
