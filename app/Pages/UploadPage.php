<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;

class UploadPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/file-upload-test.html';
    }

    function upload($filePath)
    {
        $this->getDriver()->findElement(WebDriverBy::id('itsafile'))->click();

        $fileInput = $this->getDriver()->findElement(WebDriverBy::id('fileinput'));
        $this->uploadFile($fileInput, $filePath);

        $this->findElement('//input[@type="submit" and @name="upload"]')->click();

        return new UploadProcessorPage();
    }
}
