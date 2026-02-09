<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry;

use App\Modules\WbpRegistry\Application\Contracts\WbpRepository;
use App\Modules\WbpRegistry\Infrastructure\Eloquent\EloquentWbpRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class WbpRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WbpRepository::class, EloquentWbpRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        Route::middleware('web')
            ->group(__DIR__.'/Interfaces/Routes/web.php');
    }
}
