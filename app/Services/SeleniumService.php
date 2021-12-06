<?php

namespace App\Services;

use Exception;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Log;

abstract class SeleniumService
{
    private RemoteWebDriver $driver;

    function __construct()
    {
        $options = $this->getOptions();
        $capabilities = $this->getDesiredCapabilities($options);
        $host = $this->getHost();

        try {
            Log::info('Criando WebDriver...');
            $this->driver = RemoteWebDriver::create($host, $capabilities);
            Log::info('WebDriver "' . $this->driver->getCapabilities()->getBrowserName() . '" criado com sucesso.');
        } catch (Exception $e) {
            Log::error('Não foi possível criar o WebDriver: ' . $e->getMessage());
        }
    }

    function __destruct()
    {
        Log::info('Encerrando WebDriver...');
        $this->driver->quit();
    }

    abstract protected function getOptions();
    abstract protected function getDesiredCapabilities($options);

    protected function getHost()
    {
        return env('SELENIUM_HOST') . ':' . env('SELENIUM_PORT');
    }

    function getDriver()
    {
        return $this->driver;
    }
}
