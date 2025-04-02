<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\GfileController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SetupAppController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\PengalamanKerjaController;

Route::view('/', 'welcome')->name('welcome');

Route::view('dashboard', 'welcome')->name('dashboard');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';


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

// Article

Route::get('articles-index', [ArticleController::class, 'index'])->name('articles.index');

Route::middleware(['auth'])->group(function () {
    // Articles
    Route::resource('articles', ArticleController::class)->except('index');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Comments
    Route::post('articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Likes
    Route::post('articles/{article}/like', [LikeController::class, 'toggle'])->name('articles.like');

    Route::post('/upload-gambar', [ArticleController::class, 'uploadGambar']);
});



// Create member table from another table
Route::get('create_user', [SetupAppController::class, 'createUser']);
Route::get('create_member', [SetupAppController::class, 'createMember']);

Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::controller(FileUploadController::class)->group(function () {
    Route::get('/uploads', 'index')->name('uploads.index');
    Route::post('/uploads', 'store')->name('uploads.store');
    Route::delete('/uploads/{id}', 'destroy')->name('uploads.destroy');
    Route::get('/uploads/download/{id}', 'downloadMedia')->name('uploads.download');
    Route::get('/uploads/delete-title/{id}', 'deleteTitle')->name('uploads.delete-title');
});


Route::get('/welcome-email', [EmailController::class, 'welcomeEmail']);


// profils
Route::middleware(['auth'])->group(function () {
    // Profil Routes
    Route::get('/profils', [ProfilController::class, 'index'])->name('profils.index');
    Route::get('/profils/create', [ProfilController::class, 'create'])->name('profils.create');
    Route::post('/profils', [ProfilController::class, 'store'])->name('profils.store');
    Route::get('/profils/{profil}', [ProfilController::class, 'show'])->name('profils.show');
    Route::get('/profils/{profil}/edit', [ProfilController::class, 'edit'])->name('profils.edit');
    Route::put('/profils/{profil}', [ProfilController::class, 'update'])->name('profils.update');
    Route::delete('/profils/{profil}', [ProfilController::class, 'destroy'])->name('profils.destroy');

    Route::resource('pengalaman-kerja', PengalamanKerjaController::class);
});
