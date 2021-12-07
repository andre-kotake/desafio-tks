<?php

namespace App\Services;

use App\Pages\HtmlFormPage;

class HtmlFormService
{
    function fillFormAndSubmit()
    {
        return HtmlFormPage::get()
            ->fillFormAndSubmit()
            ->getValues();
    }
}
