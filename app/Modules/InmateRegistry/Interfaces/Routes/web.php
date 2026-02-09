<?php

declare(strict_types=1);

use App\Modules\InmateRegistry\Interfaces\Http\Controllers\InmateController;
use Illuminate\Support\Facades\Route;

Route::prefix('inmate-registry')
    ->name('inmate-registry.')
    ->middleware(['auth', 'verified'])
    ->group(function (): void {
        Route::get('inmates', [InmateController::class, 'index'])->name('inmates.index');
        Route::get('inmates/create', [InmateController::class, 'create'])->name('inmates.create');
        Route::post('inmates', [InmateController::class, 'store'])->name('inmates.store');
        Route::get('inmates/{inmate}', [InmateController::class, 'show'])->name('inmates.show');
        Route::get('inmates/{inmate}/edit', [InmateController::class, 'edit'])->name('inmates.edit');
        Route::patch('inmates/{inmate}', [InmateController::class, 'update'])->name('inmates.update');
        Route::delete('inmates/{inmate}', [InmateController::class, 'destroy'])->name('inmates.destroy');
    });
