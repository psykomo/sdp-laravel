<?php

declare(strict_types=1);

use App\Modules\InmateRegistry\Interfaces\Http\Controllers\InmateController;
use Illuminate\Support\Facades\Route;

Route::prefix('inmate-registry')
    ->name('inmate-registry.')
    ->middleware(['auth', 'verified'])
    ->group(function (): void {
        Route::get('inmates', [InmateController::class, 'index'])->name('inmates.index');
        Route::post('inmates', [InmateController::class, 'store'])->name('inmates.store');
    });
