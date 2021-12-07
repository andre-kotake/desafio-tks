<?php

namespace App\Services;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class SeleniumService
{
    private $driver;

    function __construct()
    {
        try {
            $options = $this->getOptions();
            $capabilities = $this->getDesiredCapabilities($options);
            $host = $this->getHost();

            Log::info('Criando WebDriver...');
            // putenv('WEBDRIVER_CHROME_DRIVER=/storage/chromedriver');
            // $this->driver = ChromeDriver::start($capabilities);
            $this->driver = RemoteWebDriver::create($host, $capabilities);
            Log::info('WebDriver "' . $this->driver->getCapabilities()->getBrowserName() . '" criado com sucesso.');
        } catch (Throwable $th) {
            Log::error('Não foi possível criar o WebDriver: ' . $th->getMessage());
            throw $th;
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
