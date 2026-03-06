<?php

namespace App\Http\Controllers;

use App\Models\JadwalPendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yaza\LaravelGoogleDriveStorage\Gdrive;


class FormPendaftaranController extends Controller
{
    public function index(JadwalPendaftaran $program)
    {
        $program->load('inkubis');

        return inertia('FormPendaftaran', [
            'program' => $program
        ]);
    }

    public function submit(Request $request, JadwalPendaftaran $program)
    {
        // Validasi data yang dikirimkan
        // $validatedData = request()->validate([
        //     'nama' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'telepon' => 'required|string|max:20',
        //     'alamat' => 'required|string|max:500',
        //     'dokumen_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        // ]);
        $program->load('inkubis');
        $namaProgram = $program->inkubis->nama_program;

        $file = $request->file('dokumen');
        $fileName = $file->getClientOriginalName();
        
        $subFolder = $namaProgram;
        $fullPath = $subFolder. '/DokumenPendaftaran' . '/' . $fileName;

        // 1. Cek apakah folder sudah ada, karena 'put' biasanya otomatis buat)
        if (!Storage::disk('google')->exists($subFolder)) {
            // 2. Buat folder jika belum ada
            Storage::disk('google')->makeDirectory($subFolder);
        }

        // 3. Simpan file
        // Storage::disk('google')->put($fullPath, file_get_contents($file));
        Gdrive::put($fullPath, $file);
       
        return redirect()->route('welcome')->with('success', 'Pendaftaran berhasil! Data Anda sedang diproses oleh Tim Inkubator BDI Medan.');
    }   
}
