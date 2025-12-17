<?php

use App\Http\Controllers\ActivateSubscriberController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::middleware('auth')->prefix('admin')->group(function() {
    Route::controller(ActivateSubscriberController::class)->group(function() {
        Route::get('/', 'dashboard')
        ->name('admin.dashboard');
        Route::get('activate-subscriber', 'activateSubscriber')
        ->name('admin.activate-subscriber');
        Route::get('subscribers', 'index')
        ->name('admin.subscribers.index');
        Route::get('subscribers/{id}', 'show')
        ->name('admin.subscribers.show');
        Route::post('activate-subscriber', 'store')
        ->name('admin.activate-subscriber.store');
        Route::post('activate-subscriber/import', 'import')
        ->name('admin.activate-subscriber.import');
        Route::get('activate-subscriber/template', 'downloadTemplate')
        ->name('admin.activate-subscriber.template');
    });
});
