<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InkubisProgram;
use Illuminate\Http\Request;

class ManajemenProgramController extends Controller
{
    public function index()
    {
        $programs = InkubisProgram::with(['stage', 'tenants'])->get();
;

        return inertia('Admin/KelolaProgram', [
            'programs' => $programs
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'isPraKewirausahaan' => 'required|boolean',
        ]);


        $program = new InkubisProgram();

        $program->tahapan_inkubasi_id = 1;
        $program->nama_program = $validated['nama'];
        $program->deskripsi = $validated['deskripsi'];
        $program->tanggal_penyelenggaraan = $validated['tanggal'];
        $program->pra_kewirausahaan = $validated['isPraKewirausahaan'];

        $program->save();

        return redirect()->route('admin.kelola-program')->with('success', 'Program berhasil ditambahkan!');
    }
}
