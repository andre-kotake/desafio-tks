<?php

namespace App\Providers;

use App\Services\HtmlDownloadUploadService;
use App\Services\HtmlFormService;
use Illuminate\Support\ServiceProvider;
use App\Services\SeleniumService;
use App\Services\SeleniumChromeService;
use App\Services\HtmlTableRowService;

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


        $this->app->bind(HtmlDownloadUploadService::class, function ($app) {
            return new HtmlDownloadUploadService();
        });
    }

    function boot()
    {
        //
    }
}
