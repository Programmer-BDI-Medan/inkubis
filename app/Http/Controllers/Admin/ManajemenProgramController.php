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

        if ($validated['isPraKewirausahaan']) {
            $program->tahapan_inkubasi_id = 1; // Tahapan Pra-Kewirausahaan
        } else {
            $program->tahapan_inkubasi_id = 2; // Tahapan pendaftaran tenant (Pra-Inkubasi)
        }
        $program->nama_program = $validated['nama'];
        $program->deskripsi = $validated['deskripsi'];
        $program->tanggal_penyelenggaraan = $validated['tanggal'];
        $program->pra_kewirausahaan = $validated['isPraKewirausahaan'];

        $program->save();

        return redirect()->route('admin.kelola-program')->with('success', 'Program berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    { 
        $program = InkubisProgram::findOrFail($id);

        $program->tahapan_inkubasi_id = $request->idTahapan;
        $program->nama_program = $request->nama;
        $program->deskripsi = $request->deskripsi;
        $program->tanggal_penyelenggaraan = $request->tanggal;
        $program->pra_kewirausahaan = $request->isPraKewirausahaan;
        $program->save();

        return redirect()->route('admin.kelola-program')->with('success', 'Program berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $program = InkubisProgram::findOrFail($id);
        $program->delete();

        return redirect()->route('admin.kelola-program')->with('success', 'Program berhasil dihapus!');
    }
}
