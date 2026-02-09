<?php

declare(strict_types=1);

use App\Modules\WbpRegistry\Interfaces\Http\Controllers\WbpController;
use Illuminate\Support\Facades\Route;

Route::prefix('wbp-registry')
    ->name('wbp-registry.')
    ->middleware(['auth', 'verified'])
    ->group(function (): void {
        Route::get('wbp', [WbpController::class, 'index'])->name('wbp.index');
        Route::get('wbp/create', [WbpController::class, 'create'])->name('wbp.create');
        Route::post('wbp', [WbpController::class, 'store'])->name('wbp.store');
        Route::get('wbp/{wbp}', [WbpController::class, 'show'])->name('wbp.show');
        Route::get('wbp/{wbp}/edit', [WbpController::class, 'edit'])->name('wbp.edit');
        Route::patch('wbp/{wbp}', [WbpController::class, 'update'])->name('wbp.update');
        Route::delete('wbp/{wbp}', [WbpController::class, 'destroy'])->name('wbp.destroy');
    });
