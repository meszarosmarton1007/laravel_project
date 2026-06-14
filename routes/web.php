<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('logout', [AuthController::class, 'Logout'])->name('logout');

Route::middleware('guest')->controller(AuthController::class)->group(function (){
    Route::get('/register', 'showRegister')->name('show.register');
    Route::post('/register', 'Register')->name('register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/login', 'Login')->name('login');
});
