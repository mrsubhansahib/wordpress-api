<?php

use App\Http\Controllers\metalPriceController;
use App\Models\metalPrice;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('metal-pirce' , metalPriceController::class );
