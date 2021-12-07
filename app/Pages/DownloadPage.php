<?php

namespace App\Pages;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/download/download.html';
    }

    function download()
    {
        $href = $this->findElement('//a[@id="direct-download-a"]')->getAttribute('href');
        $contents = file_get_contents('https://testpages.herokuapp.com' . $href);

        Storage::disk('files')->put('Teste TKS.txt', $contents);

        $i = 0;

        while (!Storage::disk('files')->exists('Teste TKS.txt')) {
            Log::info('Verificando existência do arquivo...');
            sleep(1);
            $i++;

            if ($i == 60) {
                throw new Exception('Arquivo foi possível efetuar download do arquivo.');
            }
        }

        return storage_path('app/files') . '/Teste TKS.txt';
    }
}
