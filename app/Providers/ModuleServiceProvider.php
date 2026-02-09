<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $providers = config('modules.providers', []);

        foreach ($providers as $providerClass) {
            if (is_string($providerClass) && class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        }
    }

    public function boot(): void {}
}
