<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource for Admin.
     * Fokus: Monitoring Aset/Barang yang sering dipinjam, kontrol stok inventaris,
     * distribusi kategori, pengguna, dan log aktivitas sistem menyeluruh.
     */
    public function index()
    {
        $totalAlat = Alat::count();
        $totalStokFisik = (int) Alat::sum('stok');
        $totalKategori = Kategori::count();
        $totalPeminjaman = Peminjaman::count();
        
        $totalPelanggan = User::where('role', 'Pelanggan')->count();
        $totalPetugas = User::where('role', 'Petugas')->count();
        $totalUser = User::count();

        // Akumulasi Keuangan Kas Rental
        $totalPendapatanSewa = (float) Peminjaman::where('status', '!=', 'Dibatalkan')->sum('subtotal');
        $totalPendapatanDenda = (float) Pengembalian::sum('denda');

        // 1. Monitoring Barang/Alat yang Paling Sering Dipinjam (Top 5 Chart & Detail)
        $topAlat = DetailPeminjaman::select('alat_id', DB::raw('count(*) as total'))
            ->with('alat.kategori')
            ->groupBy('alat_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        $topAlatLabels = $topAlat->map(fn($item) => $item->alat?->nama_alat ?? 'Alat #' . $item->alat_id)->values()->toArray();
        $topAlatData = $topAlat->pluck('total')->values()->toArray();

        // 2. Monitoring Stok Alat Kritis / Menipis (Stok <= 2)
        $alatStokKritis = Alat::with('kategori')
            ->where('stok', '<=', 2)
            ->orderBy('stok')
            ->take(6)
            ->get();

        // 3. Distribusi Alat per Kategori
        $kategoriStats = Kategori::withCount('alat')->get();
        $kategoriLabels = $kategoriStats->pluck('nama_kategori')->values()->toArray();
        $kategoriCounts = $kategoriStats->pluck('alat_count')->values()->toArray();

        // 4. Statistik Pelanggan Paling Aktif
        $topUsers = Peminjaman::select('user_id', DB::raw('count(*) as total'))
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        $topUserLabels = $topUsers->map(fn($item) => $item->user?->nama ?? 'User #' . $item->user_id)->values()->toArray();
        $topUserData = $topUsers->pluck('total')->values()->toArray();

        // 5. Tren Keuangan 6 Bulan Terakhir
        $keuanganLabels = [];
        $keuanganSewa = [];
        $keuanganDenda = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::today()->startOfMonth()->subMonths($i);
            $keuanganLabels[] = $monthDate->translatedFormat('M Y');

            $sewaBulanIni = Peminjaman::whereYear('created_at', $monthDate->year)
                ->whereMonth('created_at', $monthDate->month)
                ->where('status', '!=', 'Dibatalkan')
                ->sum('subtotal');
            $keuanganSewa[] = (float) $sewaBulanIni;

            $dendaBulanIni = Pengembalian::whereYear('tgl_dikembalikan', $monthDate->year)
                ->whereMonth('tgl_dikembalikan', $monthDate->month)
                ->sum('denda');
            $keuanganDenda[] = (float) $dendaBulanIni;
        }

        // 6. Log Aktivitas Sistem Menyeluruh (CRUD Master Data & Transaksi)
        $aktivitasTerbaru = LogAktivitas::with('user')->latest()->take(7)->get();

        // 7. Ringkasan Status Peminjaman
        $statusMenunggu = Peminjaman::where('status', 'Menunggu')->count();
        $statusDiaprove = Peminjaman::where('status', 'Diaprove')->count();
        $statusDipinjam = Peminjaman::where('status', 'Dipinjam')->count();
        $statusDikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();
        $statusDibatalkan = Peminjaman::where('status', 'Dibatalkan')->count();

        $chartStatusData = [
            $statusMenunggu,
            $statusDiaprove,
            $statusDipinjam,
            $statusDikembalikan,
            $statusDibatalkan,
        ];
        $totalNonBatal = ($statusMenunggu + $statusDiaprove + $statusDipinjam + $statusDikembalikan);
        $persenSelesai = $totalNonBatal > 0 ? round(($statusDikembalikan / $totalNonBatal) * 100) : 0;

        return view('Admin.dashboard.index', compact(
            'totalAlat',
            'totalStokFisik',
            'totalKategori',
            'totalPeminjaman',
            'totalPelanggan',
            'totalPetugas',
            'totalUser',
            'totalPendapatanSewa',
            'totalPendapatanDenda',
            'topAlatLabels',
            'topAlatData',
            'alatStokKritis',
            'kategoriLabels',
            'kategoriCounts',
            'topUserLabels',
            'topUserData',
            'keuanganLabels',
            'keuanganSewa',
            'keuanganDenda',
            'aktivitasTerbaru',
            'chartStatusData',
            'persenSelesai'
        ));
    }
}
