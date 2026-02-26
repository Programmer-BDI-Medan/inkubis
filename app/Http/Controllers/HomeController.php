<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPendaftaran;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;


class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        $role = $request->user()->role;

        return match ($role) {
            'super_admin', 'admin' => redirect()->route('admin.dashboard'),
            'staff'               => redirect()->route('staff.dashboard'),
            'tenant'              => redirect()->route('tenant.dashboard'),
            default => redirect()->route('welcome')
        };
    }

    public function welcome()
    {
        $programOpen = JadwalPendaftaran::with('inkubis')
                        ->where('status', 'buka')
                        ->first();
        return Inertia::render('Welcome', [
            'canLogin'       => Route::has('login'),
            'canRegister'    => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion'     => PHP_VERSION,
            'programOpen'    => $programOpen,
        ]);
    }
}
