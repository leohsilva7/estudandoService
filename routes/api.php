<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/temperature', [\App\Http\Controllers\TemperatureController::class, 'index']);
Route::post('/temperature', [\App\Http\Controllers\TemperatureController::class, 'store']);
Route::get('/temperature/{temperature:city}', [\App\Http\Controllers\TemperatureController::class, 'show']);
Route::put('/temperature/{temperature:city}', [\App\Http\Controllers\TemperatureController::class, 'update']);
Route::delete('/temperature/{temperature:city}', [\App\Http\Controllers\TemperatureController::class, 'destroy']);
