<?php

use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\PriceController;
use Illuminate\Support\Facades\Route;

Route::get('/cars/available', [CarController::class, 'available']);

Route::apiResource('cars', CarController::class)->where(['car' => '[0-9]+']);
Route::apiResource('options', OptionController::class)->where(['option' => '[0-9]+']);
Route::apiResource('configurations', ConfigurationController::class)->where(['configuration' => '[0-9]+']);
Route::apiResource('prices', PriceController::class)->where(['price' => '[0-9]+']); 