<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->as('api.')
    ->group(function (): void {
        require_once __DIR__ . '/v1.php';
    });
