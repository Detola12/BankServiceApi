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
    Route::get('/user/{user:id}', [\App\Http\Controllers\UserController::class, 'getUserById'])->name('get-user');
    Route::post('/account/generate', [\App\Http\Controllers\AccountController::class, 'generate']);
    Route::post('/account/set-pin', [\App\Http\Controllers\AccountController::class, 'addPin'])->name('set-pin');
    Route::get('/account/{id}', [\App\Http\Controllers\AccountController::class, 'getAccountById'])->name('getAccountById');
    Route::post('/deposit', [\App\Http\Controllers\TransactionController::class, 'deposit'])->name('deposit');
    Route::post('/withdraw', [\App\Http\Controllers\TransactionController::class, 'withdraw'])->name('withdraw');
    Route::post('/transfer', [\App\Http\Controllers\TransactionController::class, 'transfer'])->name('transfer');


    Route::get('/test', function (Request $request) {
        if(ctype_digit("1019")){
            dd('Yes');
        }
        dd("no");

    });
});

