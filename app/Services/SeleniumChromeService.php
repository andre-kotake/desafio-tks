<?php

namespace App\Services;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use App\Services\SeleniumService;

class SeleniumChromeService extends SeleniumService
{
    protected function getOptions()
    {
        $options = new ChromeOptions();
        $options->addArguments(['-headless']);
        return $options;
    }

    protected function getDesiredCapabilities($options)
    {
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        return $capabilities;
    }
}
