<?php

namespace App\Services;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use App\Services\SeleniumService;
use Illuminate\Support\Facades\Log;

class SeleniumChromeService extends SeleniumService
{
    protected function getOptions()
    {
        $options = new ChromeOptions();
        $options->addArguments(['--headless']);
        $options->addArguments(['--no-sandbox']);
        $options->setExperimentalOption(
            'prefs',
            [
                'download.default_directory' => storage_path('downloads'),
                "download.prompt_for_download" => false,
                "download.directory_upgrade" => true,
                "safebrowsing.enabled" => false,
                "profile.default_content_settings.popups" => 0,
                "profile.default_content_setting_values.automatic_downloads" => 1,
            ]
        );

        return $options;
    }

    protected function getDesiredCapabilities($options)
    {
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $options);
        return $capabilities;
    }
}
