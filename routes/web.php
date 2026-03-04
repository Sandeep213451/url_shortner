<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Auth routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Protected routes (require login)
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect('/short-urls');
    });

    // Short URL management
    Route::get('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'index'])->name('short-urls.index');
    Route::get('/short-urls/create', [\App\Http\Controllers\ShortUrlController::class, 'create'])->name('short-urls.create');
    Route::post('/short-urls', [\App\Http\Controllers\ShortUrlController::class, 'store'])->name('short-urls.store');

    // Invitation
    Route::get('/invite', [\App\Http\Controllers\InvitationController::class, 'showForm'])->name('invite.form');
    Route::post('/invite', [\App\Http\Controllers\InvitationController::class, 'send'])->name('invite.send');
});

// Short URL resolution — public access
Route::get('/{code}', [\App\Http\Controllers\RedirectController::class, 'resolve'])
    ->where('code', '[A-Za-z0-9_-]+')
    ->name('short-url.resolve');
