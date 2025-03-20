<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GfileController;

Route::view('/', 'welcome');

Route::view('dashboard','welcome')->name('dashboard');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';


// File Management Routes
Route::get('/files', [FileController::class, 'index'])->name('files.index');
Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
Route::post('/files', [FileController::class, 'store'])->name('files.store');
Route::get('/files/{fileEntry}', [FileController::class, 'show'])->name('files.show');
Route::delete('/files/{fileEntry}', [FileController::class, 'destroy'])->name('files.destroy');
Route::get('/files/{fileEntry}/download', [FileController::class, 'download'])->name('files.download');


// File Management Routes
Route::get('/gfiles', [GfileController::class, 'index'])->name('gfiles.index');
Route::get('/gfiles/create', [GfileController::class, 'create'])->name('gfiles.create');
Route::post('/gfiles', [GfileController::class, 'store'])->name('gfiles.store');
Route::get('/gfiles/{fileEntry}', [GfileController::class, 'show'])->name('gfiles.show');
Route::delete('/gfiles/{fileEntry}', [GfileController::class, 'destroy'])->name('gfiles.destroy');
Route::get('/gfiles/{fileEntry}/download', [GfileController::class, 'download'])->name('gfiles.download');
