<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;

class UploadProcessorPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/uploads/fileprocessor';
    }

    function getUploadedFilename()
    {
        $uploadedFilenameElement = $this->getDriver()->findElements(WebDriverBy::id('uploadedfilename'));
        $uploadedFilename = '';

        if (count($uploadedFilenameElement) > 0) {
            $uploadedFilename = $uploadedFilenameElement[0]->getText();
        }

        return $uploadedFilename;
    }
}
