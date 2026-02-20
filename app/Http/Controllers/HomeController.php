<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPendaftaran;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role;

        return match ($role) {
            'super_admin', 'admin' => redirect()->route('admin.dashboard'),
            'staff'               => redirect()->route('staff.dashboard'),
            'tenant'              => redirect()->route('tenant.dashboard'),
            default => inertia('Welcome', [
                'programOpen' => JadwalPendaftaran::with('inkubis')
                                        ->where('status', 'buka')
                                        ->first(),
            ]),
        };
    }
}
