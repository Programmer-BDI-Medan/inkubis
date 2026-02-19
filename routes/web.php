<?php

use App\Http\Controllers\Admin\ManajemenProgramController;
use App\Http\Controllers\Admin\ManajemenUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\DashboardStaffController;
use App\Http\Controllers\Staff\StaffProgramController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
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

//Google Login
Route::get('/auth-google-redirect', [AuthenticatedSessionController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthenticatedSessionController::class, 'google_callback']);

Route::get('/dashboard', function (Request $request) {
    $role = $request->user()->role;

    // Arahkan berdasarkan role
    return match ($role) {
        'super_admin' => redirect()->route('admin.dashboard'),
        'admin'        => redirect()->route('admin.dashboard'),
        'staff'        => redirect()->route('staff.dashboard'),
        'tenant'      => redirect()->route('tenant.dashboard'),
        default       => inertia('Dashboard'), // User biasa ke dashboard standar
    };
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::get('/admin-dashboard', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'role:super_admin,admin'])->name('admin.dashboard');

Route::get('/kelola-user', [ManajemenUserController::class, 'index'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.kelola-user');
Route::patch('/kelola-user/{user}/update-role', [ManajemenUserController::class, 'updateRole'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.updateRole');
Route::delete('/kelola-user/{user}/delete', [ManajemenUserController::class, 'destroy'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.deleteUser');

Route::get('/kelola-program', [ManajemenProgramController::class, 'index'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.kelola-program');
Route::post('/add-program', [ManajemenProgramController::class, 'add'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.add-program');
Route::patch('/update-program/{id}', [ManajemenProgramController::class, 'update'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.update-program');
Route::delete('/delete-program/{id}', [ManajemenProgramController::class, 'destroy'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.delete-program');

// Staff Routes
Route::get('/staff-dashboard', [DashboardStaffController::class, 'index'])->middleware(['auth', 'role:staff'])->name('staff.dashboard');

Route::get('/staff/program/{id}', [StaffProgramController::class, 'index'])->middleware(['auth', 'role:staff'])->name('staff.program');

Route::get('/staff/detail-tenant/{id}', function ($id) {
    return Inertia::render('Staff/DetailTenant', ['id' => $id]);
})->middleware(['auth', 'role:staff'])->name('staff.detail-tenant');

Route::get('/staff/detail-phase/{id}', function ($id) {
    return Inertia::render('Staff/DetailPhase', ['id' => $id]);
})->middleware(['auth', 'role:staff'])->name('staff.detail-phase');

// Tenant Routes
Route::get('/tenant-dashboard', function () {
    return Inertia::render('Tenant/Dashboard');
})->middleware(['auth', 'role:tenant'])->name('tenant.dashboard');

require __DIR__ . '/auth.php';
