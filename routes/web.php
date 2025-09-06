<?php

use App\Http\Controllers\API\FoodController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\PreventBackHistory;

// Show Login form (default page)
Route::get('/', function () {
    return view('login');
})->name('login');

// Show Register form
Route::get('/register', function () {
    return view('register');
})->name('register');

// Handle Register form submit


// Handle Login form submit


// Protected Routes (auth + prevent back history)
Route::middleware(['auth', PreventBackHistory::class])->group(function () {

    // Dashboard page
    Route::get('/index', function () {
        return view('layouts.main');
    })->name('index');

    // Food list
    Route::get('showfood', [HomeController::class, 'ShowFood'])->name('showfood');

    // Logout
    Route::get('editfood/{id}', [HomeController::class, 'EditFood'])->name('editfood');

     Route::get('addFood', [HomeController::class, 'addFood'])->name('addFood');
 
});



