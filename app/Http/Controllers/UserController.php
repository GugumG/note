<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

/**
 * ============================================================
 * FILE: app/Http/Controllers/UserController.php
 * Tujuannya: Menangani CRUD (Create, Read, Update, Delete) untuk Manajemen User.
 *            Akses dibatasi hanya untuk role 'administrator'.
 * ============================================================
 */
class UserController extends Controller
{
    /**
     * Pastikan hanya Administrator yang bisa mengakses controller ini.
     * Menggunakan pengecekan simpel di setiap method (KISS).
     */
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda bukan Administrator.');
        }
    }

    /**
     * Menampilkan daftar semua user.
     */
    public function index()
    {
        $this->checkAdmin();
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user baru.
     */
    public function create()
    {
        $this->checkAdmin();
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:administrator,leader,member'],
            'team' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'team' => $request->team,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        $this->checkAdmin();
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string', 'in:administrator,leader,member'],
            'team' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'team' => $request->team,
        ]);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $this->checkAdmin();

        // [Integritas Data] Jangan biarkan admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
