<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->as('v1.')
    ->group(function (): void {
        Route::get('/users/me', [UserController::class, 'me'])->name('users.me');
    });
