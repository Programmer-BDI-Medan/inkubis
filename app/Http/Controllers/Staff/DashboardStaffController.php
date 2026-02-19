<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InkubisProgram;
use Illuminate\Support\Facades\Auth;

class DashboardStaffController extends Controller
{
    public function index()
    {   
        $user = Auth::user();
        $programs = InkubisProgram::with(['stage'])->get();;
        return inertia('Staff/Dashboard', [
            'programs' => $programs,
            'user' => $user,
        ]);
    }
}
