<?php

namespace App\Pages;

use Facebook\WebDriver\WebDriverBy;

class HtmlDownloadPage extends BasePage
{
    function getUrl(): string
    {
        return 'https://testpages.herokuapp.com/styled/download/download.html';
    }

    function download(){
        
    }

}
