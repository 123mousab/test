<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Trader\Application\Http\Controllers\TraderController;

Route::prefix('trader')->group(function (){

    Route::get('index', [TraderController::class, 'index']);
    Route::post('store', [TraderController::class, 'store']);
    Route::post('{id}/update', [TraderController::class, 'update']);
});
