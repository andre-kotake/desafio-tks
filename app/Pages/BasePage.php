<?php

namespace App\Pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\App;
use App\Services\SeleniumService;
use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;
use Illuminate\Support\Facades\Log;

abstract class BasePage
{
    private $driver;

    protected function __construct()
    {
        $this->driver = App::make(SeleniumService::class)->getDriver();
    }

    abstract function getUrl(): string;

    static function get()
    {
        $s = new static();
        $s->openPage();

        return $s;
    }

    protected function getDriver()
    {
        return $this->driver;
    }

    function openPage()
    {
        $this->getDriver()->get($this->getUrl());
        $this->waitUntilUrl();
    }

    protected function waitUntilUrl()
    {
        Log::info('Waiting for ' . $this->getUrl() . ' to load...');
        $this->getDriver()->wait(120)->until(
            WebDriverExpectedCondition::urlIs($this->getUrl())
        );
        Log::info('Page ' . $this->getUrl() . ' loaded.');
    }

    protected function findElement($xpath)
    {
        return $this->getDriver()->findElement(WebDriverBy::xpath($xpath));
    }

    protected function findChildElement($parent, $childXPath)
    {
        return $parent->findElement(WebDriverBy::xpath($childXPath));
    }

    protected function sendKeysToElement($element, $keys)
    {
        return $element->clear()->sendKeys($keys);
    }

    protected function selectCheckboxOptions($element, $values)
    {
        $checkboxes = new WebDriverCheckboxes($element);
        $checkboxes->deselectAll();

        foreach ($values as $value) {
            $checkboxes->selectByValue($value);
        }

        return $element;
    }

    protected function selectRadioButtonValue($element, $value)
    {
        $radios = new WebDriverRadios($element);
        $radios->selectByValue($value);

        return $element;
    }


    protected function setSelectOptions($element, $values)
    {
        $select = new WebDriverSelect($element);

        if ($element->getAttribute('multiple') == 'multiple') {
            $select->deselectAll();
        }

        foreach ($values as $value) {
            $select->selectByValue($value);
        }

        return $element;
    }

    protected function uploadFile($fileInput, $filePath)
    {
        $fileInput->setFileDetector(new LocalFileDetector());
        $fileInput->clear()->sendKeys($filePath);
        return $fileInput;
    }
}
