<?php

use App\Modules\Games\Controllers\Api\GamesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void
{
    Route::prefix('games')->group(function (): void
    {
        Route::get('{alias}', [GamesController::class, 'detailByAlias']);
        Route::get('', [GamesController::class, 'getAll']);
    });
});
