<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Book;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/books', function () {
    return Book::all();
})->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/books', [BookController::class, 'store'])->middleware('can:create,App\Models\Book');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('can:delete,App\Models\Book');
    Route::put('/books/{book}', [BookController::class, 'update'])->middleware('can:update,App\Models\Book');
});

require __DIR__.'/auth.php';
