<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InkubisProgram;
use Illuminate\Http\Request;

class StaffProgramController extends Controller
{
    public function index(Request $request)
    {
        
        $program = InkubisProgram::findOrFail($request->id);
        
        return inertia('Staff/Program', [
            'id' => $request->id,
            'program' => $program,
        ]);
    }
}
