<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DownloadUploadService;
use App\Services\HtmlFormService;
use App\Services\SeleniumService;
use App\Services\SeleniumChromeService;
use App\Services\HtmlTableRowService;
use App\Services\PdfToCsvService;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    function register()
    {
        $this->app->singleton(SeleniumService::class, function ($app) {
            return new SeleniumChromeService();
        });

        $this->app->bind(HtmlTableRowService::class, function ($app) {
            return new HtmlTableRowService();
        });

        $this->app->bind(HtmlFormService::class, function ($app) {
            return new HtmlFormService();
        });

        $this->app->bind(DownloadUploadService::class, function ($app) {
            return new DownloadUploadService();
        });

        $this->app->bind(PdfToCsvService::class, function ($app) {
            return new PdfToCsvService();
        });
    }

    function boot()
    {
        //
    }
}
