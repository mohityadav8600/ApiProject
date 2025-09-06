<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\API\FoodController;
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\HomeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register')->name('api.register');
        Route::post('login', 'login')->name('api.login');
     Route::post('logout', 'logout')->name('api.logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('food', FoodController::class);
});

Route::middleware(['auth', PreventBackHistory::class])->group(function (){

    
});







