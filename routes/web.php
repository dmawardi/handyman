<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobRequestController;
use App\Http\Controllers\Admin\AuthController;
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
Route::get('/job-requests/create', [JobRequestController::class, 'create'])->name('job-requests.create');
Route::post('/job-requests', [JobRequestController::class, 'store'])->name('job-requests.store');

// Authentication routes
Route::middleware('auth')->group(function () {
    // Job request routes
    Route::get('/job-requests', [JobRequestController::class, 'index'])->name('job-requests.index');
    Route::get('/job-requests/{jobRequest}', [JobRequestController::class, 'show'])->name('job-requests.show');
    Route::get('/job-requests/{jobRequest}/edit', [JobRequestController::class, 'edit'])->name('job-requests.edit');
    // Other authenticated routes...
    Route::patch('/job-requests/{jobRequest}', [JobRequestController::class, 'update'])->name('job-requests.update');
    Route::delete('/job-requests/{jobRequest}', [JobRequestController::class, 'destroy'])->name('job-requests.destroy');
});

// Public job request routes


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN ROUTES
// 
// Public routes
Route::get('/admin', function () {
    // Redirect to the admin dashboard
    return redirect()->route('admin.dashboard');
})->name('admin.home');
// Login routes
Route::get('/admin/login', [AuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'store'])->name('admin.login.store');

// Admin authenticated routes
Route::middleware(['auth', 'admin'])->group(function () {
    // login and logout routes
    Route::post('/admin/logout', [AuthController::class, 'destroy'])->name('admin.logout');
    // Admin dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

require __DIR__.'/auth.php';
