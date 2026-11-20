<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::latest()->whereIn('role', ['Admin', 'Petugas'])->paginate(15);
        return view('Admin.user.index', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:Admin,Petugas',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid (contoh: user@gmail.com).',
            'email.unique' => 'Email ini sudah terdaftar. Gunakan alamat email yang berbeda.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role harus Admin atau Petugas.',
        ]);

        $data['password'] = Hash::make($data['password']);

        $newUser = User::create($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Tambah User',
            'keterangan' => 'User baru "' . $newUser->nama . '" (' . $newUser->role . ') berhasil ditambahkan.',
        ]);

        return redirect()->back()->with('success', 'User "' . $newUser->nama . '" berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:Admin,Petugas',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password baru minimal terdiri dari 6 karakter.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role harus Admin atau Petugas.',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update User',
            'keterangan' => 'Data user "' . $user->nama . '" (' . $user->role . ') berhasil diperbarui.',
        ]);

        return redirect()->back()->with('success', 'Data user "' . $user->nama . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // UKK Tantangan 4: Data user yang sudah/sedang meminjam tidak bisa dihapus (hard delete)
        if ($user->peminjaman()->exists()) {
            return redirect()->back()->with('error', 'User "' . $user->nama . '" tidak dapat dihapus karena sudah memiliki riwayat atau sedang melakukan peminjaman.');
        }

        $nama = $user->nama;
        $user->delete();

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Hapus User',
            'keterangan' => 'User "' . $nama . '" berhasil dihapus dari sistem.',
        ]);

        return redirect()->back()->with('success', 'User "' . $nama . '" berhasil dihapus.');
    }
}
