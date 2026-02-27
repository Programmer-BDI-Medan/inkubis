<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InkubisProgram;
use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class ManajemenProgramController extends Controller
{
    public function index()
    {
        $programs = InkubisProgram::with(['stage', 'tenants'])->get();
        $jadwalPendaftaran = JadwalPendaftaran::with('inkubis')->get();

        return inertia('Admin/KelolaProgram', [
            'programs' => $programs,
            'jadwalPendaftaran' => $jadwalPendaftaran
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

        Storage::disk('google')->makeDirectory($validated['nama']);


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

        // google
        $oldfolderpath = $program->nama_program;
        $newfolder = $request->nama;
        Gdrive::renameDir($oldfolderpath, $newfolder);

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

    public function addPendaftaran(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:inkubis_programs,id',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after:tanggal_buka',
            'status' => 'required|in:buka,tutup',
        ]);
        $pendaftaran = new JadwalPendaftaran();
        $pendaftaran->inkubis_program_id = $validated['program_id'];
        $pendaftaran->tanggal_buka = $validated['tanggal_buka'];
        $pendaftaran->tanggal_tutup = $validated['tanggal_tutup'];
        $pendaftaran->status = $validated['status'];
        $pendaftaran->save();
        // JadwalPendaftaran::create($request->all());
        
    }

    public function updatePendaftaran(Request $request, $id)
    {
        $pendaftaran = JadwalPendaftaran::findOrFail($id);
        $pendaftaran->tanggal_buka = $request->tanggal_buka;
        $pendaftaran->tanggal_tutup = $request->tanggal_tutup;
        $pendaftaran->status = $request->status;
        $pendaftaran->save();
    }
}
