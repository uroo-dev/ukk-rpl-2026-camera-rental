<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource for Petugas.
     * Fokus Penuh: Operasional transaksi peminjaman harian, verifikasi persetujuan,
     * monitoring serah terima, pengembalian/denda, dan log khusus peminjaman.
     */
    public function index()
    {
        $statusMenunggu = Peminjaman::where('status', 'Menunggu')->count();
        $statusDiaprove = Peminjaman::where('status', 'Diaprove')->count();
        $statusDipinjam = Peminjaman::where('status', 'Dipinjam')->count();
        $statusDikembalikan = Peminjaman::where('status', 'Dikembalikan')->count();
        $statusDibatalkan = Peminjaman::where('status', 'Dibatalkan')->count();

        // 1. Notifikasi & Antrean Pengajuan yang Menunggu Persetujuan Petugas
        $peminjamanMenunggu = Peminjaman::with('user', 'detailPeminjaman.alat')
            ->where('status', 'Menunggu')
            ->latest()
            ->get();

        // 2. Monitoring Alat Sedang Dipinjam (Untuk pengawasan pengembalian & denda)
        $peminjamanSedangDipinjam = Peminjaman::with('user', 'detailPeminjaman.alat')
            ->where('status', 'Dipinjam')
            ->orderBy('tgl_kembali', 'asc')
            ->take(6)
            ->get();

        // 3. Log Khusus Transaksi Peminjaman (Filter khusus aktivitas peminjaman saja)
        $aktivitasPeminjaman = LogAktivitas::with('user')
            ->where(function ($q) {
                $q->where('aksi', 'like', '%Pinjam%')
                  ->orWhere('aksi', 'like', '%Kembali%')
                  ->orWhere('aksi', 'like', '%Batal%')
                  ->orWhere('aksi', 'like', '%Approve%');
            })
            ->latest()
            ->take(8)
            ->get();

        // 4. Grafik Status Operasional Peminjaman
        $chartStatusData = [
            $statusMenunggu,
            $statusDiaprove,
            $statusDipinjam,
            $statusDikembalikan,
            $statusDibatalkan,
        ];
        $totalNonBatal = ($statusMenunggu + $statusDiaprove + $statusDipinjam + $statusDikembalikan);
        $persenSelesai = $totalNonBatal > 0 ? round(($statusDikembalikan / $totalNonBatal) * 100) : 0;

        // 5. Grafik Keuangan Peminjaman & Denda (UKK 3d)
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

        // Keuangan Kas
        $totalPendapatanSewa = (float) Peminjaman::where('status', '!=', 'Dibatalkan')->sum('subtotal');
        $totalPendapatanDenda = (float) Pengembalian::sum('denda');

        return view('Petugas.dashboard.index', compact(
            'statusMenunggu',
            'statusDiaprove',
            'statusDipinjam',
            'statusDikembalikan',
            'statusDibatalkan',
            'totalPendapatanSewa',
            'totalPendapatanDenda',
            'peminjamanMenunggu',
            'peminjamanSedangDipinjam',
            'aktivitasPeminjaman',
            'chartStatusData',
            'persenSelesai',
            'keuanganLabels',
            'keuanganSewa',
            'keuanganDenda'
        ));
    }
}
