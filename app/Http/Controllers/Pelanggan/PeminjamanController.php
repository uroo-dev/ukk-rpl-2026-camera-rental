<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('user.pelanggan', 'detailPeminjaman.alat')
            ->where('user_id', Auth::id());

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();

        return view('Pelanggan.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Show the invoice for customer.
     */
    public function invoice(Peminjaman $peminjaman)
    {
        if (Auth::user()->role === 'Pelanggan' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses invoice tidak diizinkan.');
        }

        $peminjaman->load('user.pelanggan', 'detailPeminjaman.alat', 'pengembalian');
        $tglPinjam = Carbon::parse($peminjaman->tgl_pinjam)->startOfDay();
        $tglKembali = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
        $lama = max(1, $tglPinjam->diffInDays($tglKembali) ?: 1);

        return view('Pelanggan.invoice.index', compact('peminjaman', 'lama'));
    }

    /**
     * Show the invoice for admin/petugas.
     */
    public function invoiceAll(Peminjaman $peminjaman)
    {
        $peminjaman->load('user.pelanggan', 'detailPeminjaman.alat', 'pengembalian');
        $tglPinjam = Carbon::parse($peminjaman->tgl_pinjam)->startOfDay();
        $tglKembali = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
        $lama = max(1, $tglPinjam->diffInDays($tglKembali) ?: 1);

        return view('Petugas.invoice.index', compact('peminjaman', 'lama'));
    }

    /**
     * Export Invoice to PDF via DomPDF.
     */
    public function invoicePdf(Peminjaman $peminjaman)
    {
        if (Auth::user()->role === 'Pelanggan' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Akses invoice tidak diizinkan.');
        }

        $peminjaman->load('user.pelanggan', 'detailPeminjaman.alat', 'pengembalian');
        $tglPinjam = Carbon::parse($peminjaman->tgl_pinjam)->startOfDay();
        $tglKembali = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
        $lama = max(1, $tglPinjam->diffInDays($tglKembali) ?: 1);

        $pdf = Pdf::loadView('Petugas.invoice.pdf', compact('peminjaman', 'lama'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice_' . $peminjaman->kode . '.pdf');
    }

    /**
     * Store a newly created loan in storage (Single Equipment).
     */
    public function store(Request $request, Alat $alat)
    {
        $request->validate([
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'metode_pembayaran' => 'nullable|string|max:100',
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'jaminan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:255',
        ], [
            'tgl_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tgl_pinjam.date' => 'Format tanggal pinjam tidak valid.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tgl_kembali.date' => 'Format tanggal kembali tidak valid.',
            'tgl_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $cekPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam'])
            ->exists();

        if ($cekPeminjaman) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif yang belum selesai. Harap selesaikan terlebih dahulu.');
        }

        if ($alat->stok <= 0) {
            return back()->with('error', 'Maaf, stok alat ' . $alat->nama_alat . ' saat ini sedang kosong.');
        }

        $tglPinjam = Carbon::parse($request->tgl_pinjam)->startOfDay();
        $tglKembali = Carbon::parse($request->tgl_kembali)->startOfDay();
        $lamaHari = max(1, $tglPinjam->diffInDays($tglKembali) ?: 1);
        $subtotal = $alat->harga_sewa * $lamaHari;
        $kode = 'PJM-' . now()->format('YmdHis') . rand(10, 99);

        $metode = $request->input('metode_pembayaran', 'Tunai di Toko');
        $bayar = (float) $request->input('jumlah_bayar', 0);
        $jaminan = $request->input('jaminan', 'KTP Asli / Identitas Terverifikasi');

        $statusBayar = 'Belum Bayar';
        if ($bayar >= $subtotal) {
            $statusBayar = 'Lunas (Rp ' . number_format($bayar, 0, ',', '.') . ')';
        } elseif ($bayar > 0) {
            $statusBayar = 'DP (Rp ' . number_format($bayar, 0, ',', '.') . ')';
        } else {
            $statusBayar = 'Bayar di Toko saat Ambil';
        }

        $bukti = "Jaminan: {$jaminan} | Metode: {$metode} | Status: {$statusBayar}";
        if ($request->filled('catatan')) {
            $bukti .= " | Catatan: " . $request->input('catatan');
        }
        $bukti = mb_substr($bukti, 0, 255);

        DB::transaction(function () use ($request, $alat, $lamaHari, $subtotal, $kode, $bukti) {
            $peminjaman = Peminjaman::create([
                'kode' => $kode,
                'user_id' => Auth::id(),
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'Menunggu',
                'subtotal' => $subtotal,
                'nama_bukti' => $bukti,
            ]);

            DetailPeminjaman::create([
                'peminjaman_id' => $peminjaman->id,
                'alat_id' => $alat->id,
                'lama_pinjam' => $lamaHari,
                'total' => $subtotal,
                'status' => 'Menunggu',
            ]);

            // Pengurangan stok alat saat peminjaman diajukan
            $alat->decrement('stok', 1);
        });

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Pengajuan Peminjaman',
            'keterangan' => 'Pengajuan peminjaman ' . $alat->nama_alat . ' (' . $kode . ') berhasil diajukan.',
        ]);

        return redirect()->route('riwayat.peminjaman')->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
    }

    /**
     * Store multiple equipment loan (UKK Multi Peminjaman & Pembayaran).
     */
    public function storeMulti(Request $request)
    {
        $request->validate([
            'alat_ids' => 'required|array|min:1',
            'alat_ids.*' => 'required|exists:alat,id',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali' => 'required|date|after_or_equal:tgl_pinjam',
            'metode_pembayaran' => 'nullable|string|max:100',
            'jumlah_bayar' => 'nullable|numeric|min:0',
            'jaminan' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:255',
        ], [
            'alat_ids.required' => 'Pilih minimal satu alat kamera untuk dipinjam.',
            'alat_ids.min' => 'Pilih minimal satu alat kamera untuk dipinjam.',
            'tgl_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tgl_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tgl_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $cekPeminjaman = Peminjaman::where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam'])
            ->exists();

        if ($cekPeminjaman) {
            return back()->with('error', 'Anda masih memiliki peminjaman aktif yang belum selesai. Harap selesaikan terlebih dahulu.');
        }

        $alats = Alat::whereIn('id', $request->alat_ids)->get();
        if ($alats->isEmpty()) {
            return back()->with('error', 'Pilih minimal satu alat kamera yang valid.');
        }

        foreach ($alats as $item) {
            if ($item->stok <= 0) {
                return back()->with('error', 'Maaf, stok alat "' . $item->nama_alat . '" sedang kosong.');
            }
        }

        $tglPinjam = Carbon::parse($request->tgl_pinjam)->startOfDay();
        $tglKembali = Carbon::parse($request->tgl_kembali)->startOfDay();
        $lamaHari = max(1, $tglPinjam->diffInDays($tglKembali) ?: 1);

        $grandSubtotal = 0;
        foreach ($alats as $item) {
            $grandSubtotal += ($item->harga_sewa * $lamaHari);
        }

        $metode = $request->input('metode_pembayaran', 'Tunai di Toko');
        $bayar = (float) $request->input('jumlah_bayar', 0);
        $jaminan = $request->input('jaminan', 'KTP Asli / Identitas Terverifikasi');

        $statusBayar = 'Belum Bayar';
        if ($bayar >= $grandSubtotal) {
            $statusBayar = 'Lunas (Rp ' . number_format($bayar, 0, ',', '.') . ')';
        } elseif ($bayar > 0) {
            $statusBayar = 'DP (Rp ' . number_format($bayar, 0, ',', '.') . ')';
        } else {
            $statusBayar = 'Bayar di Toko saat Ambil';
        }

        $bukti = "Jaminan: {$jaminan} | Metode: {$metode} | Status: {$statusBayar}";
        if ($request->filled('catatan')) {
            $bukti .= " | Catatan: " . $request->input('catatan');
        }
        $bukti = mb_substr($bukti, 0, 255);

        $kode = 'PJM-' . now()->format('YmdHis') . rand(10, 99);

        DB::transaction(function () use ($request, $alats, $lamaHari, $grandSubtotal, $kode, $bukti) {
            $peminjaman = Peminjaman::create([
                'kode' => $kode,
                'user_id' => Auth::id(),
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali' => $request->tgl_kembali,
                'status' => 'Menunggu',
                'subtotal' => $grandSubtotal,
                'nama_bukti' => $bukti,
            ]);

            foreach ($alats as $a) {
                $itemTotal = $a->harga_sewa * $lamaHari;
                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $a->id,
                    'lama_pinjam' => $lamaHari,
                    'total' => $itemTotal,
                    'status' => 'Menunggu',
                ]);

                // Kurangi stok masing-masing alat yang dipinjam
                $a->decrement('stok', 1);
            }
        });

        $alatNames = $alats->pluck('nama_alat')->implode(', ');
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Pengajuan Peminjaman Multi-Alat',
            'keterangan' => 'Pengajuan peminjaman ' . $alats->count() . ' alat (' . $alatNames . ') Kode: ' . $kode . ' Total: Rp ' . number_format($grandSubtotal, 0, ',', '.'),
        ]);

        return redirect()->route('riwayat.peminjaman')->with('success', 'Pengajuan peminjaman ' . $alats->count() . ' alat berhasil dikirim. Menunggu persetujuan petugas.');
    }

    /**
     * Display personal activity logs for Pelanggan (UKK: Log Aktifitas Peminjam).
     */
    public function logSaya()
    {
        $log = LogAktivitas::where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('Pelanggan.log.index', compact('log'));
    }
}
