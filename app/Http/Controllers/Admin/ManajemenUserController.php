<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManajemenUserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'role')
            ->get();

        return inertia('Admin/KelolaUser', [
            'users' => $users,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:super_admin,admin,staff,tenant,user',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.kelola-user')->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
