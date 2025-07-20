<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])
    ->as('api.')
    ->group(function (): void {
        require __DIR__ . '/v1.php';
    });
