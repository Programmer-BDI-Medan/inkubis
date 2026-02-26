<?php

use App\Http\Controllers\Admin\ManajemenProgramController;
use App\Http\Controllers\Admin\ManajemenUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Staff\DashboardStaffController;
use App\Http\Controllers\Staff\StaffProgramController;
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
})->name('welcome');
Route::get('/', [HomeController::class, 'welcome'])->name('welcome');

//Google Login
Route::get('/auth-google-redirect', [AuthenticatedSessionController::class, 'google_redirect']);
Route::get('/auth-google-callback', [AuthenticatedSessionController::class, 'google_callback']);

Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');

// Route::get('/form-pendaftaran/{program}', [FormPendaftaranController::class, 'index'])->middleware(['auth'])->name('form-pendaftaran');
route::get('/form-pendaftaran', function () {
    return Inertia::render('FormPendaftaran');
})->middleware(['auth'])->name('form-pendaftaran');

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
Route::post('/pendaftaran-program', [ManajemenProgramController::class, 'addPendaftaran'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.add-pendaftaran');
Route::put('/pendaftaran-program/{id}', [ManajemenProgramController::class, 'updatePendaftaran'])->middleware(['auth', 'role:super_admin,admin'])->name('admin.update-pendaftaran');
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

Route::get('/tenant-profile', function () {
    return Inertia::render('Tenant/Profile');
})->middleware(['auth', 'role:tenant'])->name('tenant.profile');

require __DIR__ . '/auth.php';
