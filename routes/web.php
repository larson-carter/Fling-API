<?php

use App\Http\Controllers\DeviceMacController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Route::post('/device-macs', [DeviceMacController::class, 'store']);

// Disabled CSRF Protection for testing purposes
Route::post('/device-macs', [DeviceMacController::class, 'store'])->withoutMiddleware(['csrf']);
