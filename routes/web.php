<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobRequestController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/services', function () {
    return view('services');
})->name('services');

// Job request routes
Route::get('/job-requests', [JobRequestController::class, 'index'])->name('job-requests.index');
Route::get('/job-requests/create', [JobRequestController::class, 'create'])->name('job-requests.create');
Route::post('/job-requests', [JobRequestController::class, 'store'])->name('job-requests.store');
Route::get('/job-requests/{jobRequest}', [JobRequestController::class, 'show'])->name('job-requests.show');
Route::patch('/job-requests/{jobRequest}', [JobRequestController::class, 'update'])->name('job-requests.update');
Route::delete('/job-requests/{jobRequest}', [JobRequestController::class, 'destroy'])->name('job-requests.destroy');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
