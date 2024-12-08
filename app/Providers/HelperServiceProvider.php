<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once app_path('Helpers/RegionConverter.php');
        require_once app_path('Helpers/UserMessageDataExtractor.php');

        $this->app->singleton('RegionConverter', function () {
            return \RegionConverter::class;
        });

        $this->app->singleton('UserMessageDataExtractor', function () {
            return \UserMessageDataExtractor::class;
        });
    }
}
