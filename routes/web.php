<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpenseController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [ExpenseController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::resource('expenses', ExpenseController::class);
});

Route::get('/export-expenses-csv', [ExpenseController::class, 'exportCsv']);

Route::get('/dashboard/no-cache-url', function () {
    return 'Cache Disabled!';
})->middleware(\App\Http\Middleware\NoCacheMiddleware::class);


require __DIR__ . '/auth.php';