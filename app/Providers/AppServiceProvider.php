<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SeleniumService;
use App\Services\SeleniumChromeService;
use App\Services\HtmlTableRowService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(SeleniumService::class, function ($app) {
            return new SeleniumChromeService();
        });

        $this->app->bind(HtmlTableRowService::class, function ($app) {
            return new HtmlTableRowService();
        });
    }

    public function boot()
    {
        //
    }
}
