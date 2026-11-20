<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerandaController extends Controller
{
    /**
     * Display a listing of the equipment catalog on the homepage.
     */
    public function index(Request $request)
    {
        $query = Alat::with('kategori');

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        $alat = $query->latest()->paginate(12)->withQueryString();

        $cekPeminjaman = false;
        if (Auth::check() && Auth::user()->role === 'Pelanggan') {
            $cekPeminjaman = Peminjaman::where('user_id', Auth::id())
                ->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam'])
                ->exists();
        }

        return view('Pelanggan.beranda.index', compact('alat', 'cekPeminjaman'));
    }

    /**
     * Display single equipment detail page.
     * UKK Tantangan 5: Riwayat aktivitas objek utama (Read)
     */
    public function show(Alat $alat)
    {
        $alat->load('kategori');

        $cekPeminjaman = false;
        if (Auth::check()) {
            $cekPeminjaman = Peminjaman::where('user_id', Auth::id())
                ->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam'])
                ->exists();

            // Log read activity
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'Lihat Detail Alat',
                'keterangan' => Auth::user()->nama . ' melihat detail alat "' . $alat->nama_alat . '"',
            ]);
        }

        return view('Pelanggan.alat.index', compact('alat', 'cekPeminjaman'));
    }
}
