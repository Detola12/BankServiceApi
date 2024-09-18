<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'index'])->name('register');

Route::post('/login', [\App\Http\Controllers\Auth\SessionController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [\App\Http\Controllers\Auth\SessionController::class, 'logout'])->name('logout');
});

/*Route::get('/user', function () {
    dd(session()->all());
});*/
