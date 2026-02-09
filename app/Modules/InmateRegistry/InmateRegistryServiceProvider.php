<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry;

use App\Modules\InmateRegistry\Application\Contracts\InmateRepository;
use App\Modules\InmateRegistry\Infrastructure\Eloquent\EloquentInmateRepository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class InmateRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(InmateRepository::class, EloquentInmateRepository::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        Route::middleware('web')
            ->group(__DIR__.'/Interfaces/Routes/web.php');
    }
}
