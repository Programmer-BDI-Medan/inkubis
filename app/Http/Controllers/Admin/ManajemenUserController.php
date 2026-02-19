<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InkubisProgram;
use App\Models\InkubisTenant;
use App\Models\User;
use Illuminate\Http\Request;

class ManajemenUserController extends Controller
{
    public function index()
    {
        $users = User::with(['tenant'])
            ->select('id', 'name', 'email', 'role')
            ->get();

        $programs = InkubisProgram::select('id', 'nama_program')->get();

        return inertia('Admin/KelolaUser', [
            'users' => $users,
            'programs' => $programs
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        
    
        $request->validate([
            'role' => 'required|in:super_admin,admin,staff,tenant,user',
        ]);

        $user->role = $request->role; 
        $user->save();


        if ($request->role ==='tenant' && $request->inkubator_id) {
            $tenant = InkubisTenant::where('user_id', $user->id)->first();
            if ($tenant) {
                $tenant->inkubis_program_id = $request->inkubator_id;
                $tenant->save();
            } else {
                $tenant = new InkubisTenant();
                $tenant->user_id = $user->id;
                $tenant->inkubis_program_id = $request->inkubator_id;
                $tenant->nama_tenant = '-';
                $tenant->jenis_produk = '-';
                $tenant->deskripsi = '-';
                $tenant->save();
            }
        }

        return redirect()->route('admin.kelola-user')->with('success', 'Role pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Hapus user
        $user->delete();

        return redirect()->route('admin.kelola-user')->with('success', 'Pengguna berhasil dihapus.');
    }
}
