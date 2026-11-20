<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login()
    {
        return view('Auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register()
    {
        return view('Auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login_proses(Request $request)
    {
        $credentials = $request->validate([
            'nama' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'nama.required' => 'Nama pengguna wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'Login',
                'keterangan' => 'Pengguna ' . Auth::user()->nama . ' berhasil login ke sistem.',
            ]);

            $user = Auth::user();
            return match ($user->role) {
                'Admin' => redirect()->route('admin.dashboard.index')->with('success', 'Selamat Datang, ' . $user->nama . '!'),
                'Petugas' => redirect()->route('petugas.dashboard.index')->with('success', 'Selamat Datang, ' . $user->nama . '!'),
                'Pelanggan' => redirect()->route('index')->with('success', 'Selamat Datang kembali, ' . $user->nama . '!'),
                default => abort(403, 'Akses Ditolak'),
            };
        }

        return redirect()->back()
            ->onlyInput('nama')
            ->with('error', 'Nama pengguna atau password tidak sesuai. Silakan periksa kembali.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function register_proses(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:1000',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'Pelanggan',
            ]);

            Pelanggan::create([
                'user_id' => $user->id,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'],
            ]);

            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'Register',
                'keterangan' => 'Pengguna baru ' . $user->nama . ' berhasil mendaftar sebagai Pelanggan.',
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('index')->with('success', 'Pendaftaran akun berhasil! Selamat datang di MyCam.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'Logout',
                'keterangan' => 'Pengguna ' . Auth::user()->nama . ' berhasil logout.',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun.');
    }
}
