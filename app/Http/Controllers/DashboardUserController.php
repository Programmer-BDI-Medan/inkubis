<?php

namespace App\Http\Controllers;

use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;

class DashboardUserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role;

        return match ($role) {
            'super_admin', 'admin' => redirect()->route('admin.dashboard'),
            'staff'               => redirect()->route('staff.dashboard'),
            'tenant'              => redirect()->route('tenant.dashboard'),
            default => inertia('Dashboard', [
                'programOpen' => JadwalPendaftaran::with('inkubis')
                                        ->where('status', 'buka')
                                        ->first(),
            ]),
        };
    }
}
