<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Google Login
Route::get('/auth-google-redirect', [AuthenticatedSessionController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthenticatedSessionController::class, 'google_callback']);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::get('/admin-dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');


// Staff Routes
Route::get('/staff-dashboard', function () {
    return Inertia::render('Staff/Dashboard');
})->middleware(['auth', 'role:staff'])->name('staff.dashboard');



// Tenant Routes

require __DIR__ . '/auth.php';
