<?php

namespace App\Pages;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\App;
use App\Services\SeleniumService;
use Illuminate\Support\Facades\Log;

abstract class BasePage
{
    private RemoteWebDriver $driver;

    protected function __construct()
    {
        $this->driver = App::make(SeleniumService::class)->getDriver();
    }

    /**
     * Cria a instancia do objeto da página e carrega a página no browser atual.
     */
    public static function get()
    {
        $s = new static();
        $s->openPage();

        return $s;
    }

    protected function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

    protected abstract function getUrl(): string;


    public function openPage()
    {
        Log::info('Navegando para: ' . $this->getUrl());
        $this->getDriver()->get($this->getUrl());
    }
}
