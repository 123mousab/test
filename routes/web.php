<?php

use App\Http\Controllers\Admin\SubscribeController;
use Illuminate\Support\Facades\Route;


// Route::get('/cooking_today', [SubscribeController::class, 'generateCookingToday']);

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');


Route::get('/storage_shortcut', function () {
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
//    \Illuminate\Support\Facades\Artisan::call('migrate:fresh --force');
//    symlink(storage_path('app/public'), public_path('/storage'));
    \Illuminate\Support\Facades\Artisan::call('storage:link');
//    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return 'success';
});
