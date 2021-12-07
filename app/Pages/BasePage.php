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
    private RemoteWebDriver $driver;

    protected function __construct()
    {
        $this->driver = App::make(SeleniumService::class)->getDriver();
    }

    abstract function getUrl(): string;

    /**
     * Cria a instancia do objeto da página e carrega a página no browser atual.
     */
    static function get()
    {
        $s = new static();
        $s->openPage();

        return $s;
    }

    protected function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

    function openPage()
    {
        Log::info('Navigating to: ' . $this->getUrl());
        $this->getDriver()->get($this->getUrl());
        $this->waitUntilUrl();
    }

    protected function waitUntilUrl()
    {
        Log::info('Waiting for ' . $this->getUrl() . ' ...');
        $this->getDriver()->wait(120)->until(
            WebDriverExpectedCondition::urlIs($this->getUrl())
        );
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
