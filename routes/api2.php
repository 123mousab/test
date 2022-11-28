<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\SubscribeController;

Route::post('delete/subscribe_data', [SubscribeController::class, 'destroy']);
