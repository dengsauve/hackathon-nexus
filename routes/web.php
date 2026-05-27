<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->whereIn('provider', ['github', 'google'])
        ->name('auth.social.redirect');

    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])
        ->whereIn('provider', ['github', 'google'])
        ->name('auth.social.callback');
});

Route::middleware('auth')->group(function () {
    Route::view('/portal', 'portal')->name('portal');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
