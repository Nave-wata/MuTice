<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

final class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $helperFiles = glob(app_path('Helpers').'/*.php');

        if ($helperFiles === false) {
            return;
        }

        foreach ($helperFiles as $helperFile) {
            require_once $helperFile;
        }
    }
}
