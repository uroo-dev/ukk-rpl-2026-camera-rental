<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class LogController extends Controller
{
    /**
     * Display a listing of all system activity logs for Admin.
     */
    public function index()
    {
        $log = LogAktivitas::with('user')->latest()->paginate(25);
        return view('Admin.log.index', compact('log'));
    }

    /**
     * Display activity logs khusus transaksi peminjaman untuk Petugas.
     */
    public function user()
    {
        $log = LogAktivitas::with('user')
            ->where(function ($q) {
                $q->where('aksi', 'like', '%Pinjam%')
                  ->orWhere('aksi', 'like', '%Kembali%')
                  ->orWhere('aksi', 'like', '%Batal%')
                  ->orWhere('aksi', 'like', '%Approve%');
            })
            ->latest()
            ->paginate(25);

        return view('Petugas.log.index', compact('log'));
    }
}
