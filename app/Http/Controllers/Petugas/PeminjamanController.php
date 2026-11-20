<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of active loans (Menunggu, Diaprove, Dipinjam).
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with('user', 'detailPeminjaman.alat')
            ->whereIn('status', ['Menunggu', 'Diaprove', 'Dipinjam']);

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $peminjaman = $query->latest()->paginate(15)->withQueryString();

        return view('Petugas.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Reusable query filter for loan history and exports.
     */
    protected function filterQuery(Request $request)
    {
        $query = Peminjaman::with('user', 'detailPeminjaman.alat', 'pengembalian');

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tgl_pinjam', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tgl_pinjam', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('detailPeminjaman.alat', function ($aq) use ($search) {
                        $aq->where('nama_alat', 'like', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    /**
     * Display all loan history with search and date filters.
     */
    public function peminjaman(Request $request)
    {
        $query = $this->filterQuery($request);
        $peminjaman = $query->latest()->paginate(15)->withQueryString();

        return view('Petugas.peminjaman.peminjaman', compact('peminjaman'));
    }

    /**
     * Export loan history to PDF via DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->filterQuery($request);
        $peminjaman = $query->latest()->get();

        $totalSewa = $peminjaman->sum('subtotal');
        $totalDenda = $peminjaman->sum(fn($p) => $p->pengembalian?->denda ?? 0);
        $grandTotal = $totalSewa + $totalDenda;
        $totalBarang = $peminjaman->sum(fn($p) => $p->detailPeminjaman->count());

        $filter = [
            'tanggal_mulai' => $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : null,
            'tanggal_selesai' => $request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : null,
            'status' => $request->status ?: 'All',
            'search' => $request->search ?: null,
        ];

        $pdf = Pdf::loadView('Petugas.peminjaman.pdf', compact(
            'peminjaman',
            'totalSewa',
            'totalDenda',
            'grandTotal',
            'totalBarang',
            'filter'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Peminjaman_MyCam_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export loan history to Excel (.xlsx) via PhpSpreadsheet.
     */
    public function exportExcel(Request $request)
    {
        $query = $this->filterQuery($request);
        $peminjaman = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Peminjaman');

        // Header Dokumen Title
        $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI PEMINJAMAN ALAT - MY-CAM');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1E3A8A');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Subtitle Filter
        $tglAwal = $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : 'Semua';
        $tglAkhir = $request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : 'Sekarang';
        $statusText = $request->status && $request->status !== 'All' ? $request->status : 'Semua Status';
        $sheet->setCellValue('A2', "Periode: {$tglAwal} s/d {$tglAkhir} | Status: {$statusText} | Dicetak: " . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new Color('FF475569'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Header Kolom
        $headers = [
            'A4' => 'No',
            'B4' => 'Kode Transaksi',
            'C4' => 'Nama Pelanggan',
            'D4' => 'Email Kontak',
            'E4' => 'Daftar Alat Dipinjam',
            'F4' => 'Tgl Pinjam',
            'G4' => 'Tgl Kembali',
            'H4' => 'Tgl Dikembalikan',
            'I4' => 'Biaya Sewa (Rp)',
            'J4' => 'Denda (Rp)',
            'K4' => 'Total Tagihan (Rp)',
            'L4' => 'Status Peminjaman',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerRange = 'A4:L4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF2563EB');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(26);

        $row = 5;
        $no = 1;
        foreach ($peminjaman as $item) {
            $alatList = $item->detailPeminjaman->map(function ($d) {
                return ($d->alat?->nama_alat ?? 'Alat') . ' (' . $d->lama_pinjam . ' Hari)';
            })->implode("\n");

            $biayaSewa = (float) ($item->subtotal ?? 0);
            $denda = (float) ($item->pengembalian?->denda ?? 0);
            $total = $biayaSewa + $denda;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->kode);
            $sheet->setCellValue('C' . $row, $item->user?->nama ?? '-');
            $sheet->setCellValue('D' . $row, $item->user?->email ?? '-');
            $sheet->setCellValue('E' . $row, $alatList);
            $sheet->setCellValue('F' . $row, $item->tgl_pinjam);
            $sheet->setCellValue('G' . $row, $item->tgl_kembali);
            $sheet->setCellValue('H' . $row, $item->pengembalian?->tgl_dikembalikan ?? '-');
            $sheet->setCellValue('I' . $row, $biayaSewa);
            $sheet->setCellValue('J' . $row, $denda);
            $sheet->setCellValue('K' . $row, $total);
            $sheet->setCellValue('L' . $row, $item->status);

            $sheet->getStyle('E' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$row}:H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Alternate row background
            if ($no % 2 === 0) {
                $sheet->getStyle("A{$row}:L{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF8FAFC');
            }

            $row++;
        }

        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'TOTAL KESELURUHAN');
        $sheet->mergeCells("A{$totalRow}:H{$totalRow}");
        $sheet->setCellValue('I' . $totalRow, "=SUM(I5:I" . ($totalRow - 1) . ")");
        $sheet->setCellValue('J' . $totalRow, "=SUM(J5:J" . ($totalRow - 1) . ")");
        $sheet->setCellValue('K' . $totalRow, "=SUM(K5:K" . ($totalRow - 1) . ")");

        // Styling Summary Row & Borders
        $dataRange = "A4:L{$totalRow}";
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFE2E8F0');
        $sheet->getStyle("A{$totalRow}:L{$totalRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$totalRow}:L{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E8F0');
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(24);

        // Format Rupiah
        $sheet->getStyle("I5:K{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan_Peminjaman_MyCam_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Mark loan as Dipinjam (handover equipment with proof).
     */
    public function dipinjam(Peminjaman $peminjaman, Request $request)
    {
        $data = $request->validate([
            'nama_bukti' => 'required|string|max:255',
        ], [
            'nama_bukti.required' => 'Bukti identitas / jaminan peminjaman wajib diisi.',
        ]);

        if (!in_array($peminjaman->status, ['Diaprove', 'Menunggu'])) {
            return back()->with('error', 'Status peminjaman tidak dapat diubah ke Dipinjam.');
        }

        $peminjaman->update([
            'nama_bukti' => $data['nama_bukti'],
            'status' => 'Dipinjam',
        ]);

        $peminjaman->detailPeminjaman()->update([
            'status' => 'Dipinjam',
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Update Dipinjam',
            'keterangan' => 'Peminjaman ' . $peminjaman->kode . ' telah diserahkan (Dipinjam) dengan jaminan: ' . $data['nama_bukti'],
        ]);

        return back()->with('success', 'Alat berhasil diserahkan kepada peminjam. Status: Dipinjam.');
    }

    /**
     * Approve a pending loan with terms/guarantee/payment condition (UKK Tantangan 2).
     */
    public function diaprove(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'Menunggu') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $syarat = $request->input('syarat_jaminan') 
            ?? $request->input('nama_bukti') 
            ?? $peminjaman->nama_bukti 
            ?? 'Jaminan Identitas / Pembayaran Terverifikasi';

        $peminjaman->update([
            'status' => 'Diaprove',
            'nama_bukti' => $syarat,
        ]);

        $peminjaman->detailPeminjaman()->update([
            'status' => 'Diaprove',
        ]);

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Approve Peminjaman',
            'keterangan' => 'Petugas menyetujui pengajuan ' . $peminjaman->kode . ' dengan syarat: ' . $syarat,
        ]);

        return back()->with('success', 'Peminjaman ' . $peminjaman->kode . ' berhasil disetujui (Syarat: ' . $syarat . ').');
    }

    /**
     * Cancel/Reject a loan and restore equipment stock.
     */
    public function dibatalkan(Peminjaman $peminjaman)
    {
        if (in_array($peminjaman->status, ['Dibatalkan', 'Dikembalikan'])) {
            return back()->with('error', 'Peminjaman ini sudah tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->load('detailPeminjaman.alat');

            // Kembalikan stok alat yang sempat dipesan/dikurangi
            foreach ($peminjaman->detailPeminjaman as $detail) {
                if ($detail->alat) {
                    $detail->alat->increment('stok', 1);
                }
            }

            $peminjaman->update([
                'status' => 'Dibatalkan',
            ]);

            $peminjaman->detailPeminjaman()->update([
                'status' => 'Dibatalkan',
            ]);
        });

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Pembatalan Peminjaman',
            'keterangan' => 'Peminjaman ' . $peminjaman->kode . ' dibatalkan dan stok alat dikembalikan.',
        ]);

        return back()->with('success', 'Peminjaman ' . $peminjaman->kode . ' berhasil dibatalkan dan stok alat telah dikembalikan.');
    }

    /**
     * Process return of equipment, compute fines, and restore stock.
     */
    public function dikembalikan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status === 'Dikembalikan') {
            return back()->with('error', 'Alat pada peminjaman ini sudah pernah dikembalikan.');
        }

        $tglKembali = Carbon::parse($peminjaman->tgl_kembali)->startOfDay();
        $hariIni = now()->startOfDay();
        $terlambat = $hariIni->greaterThan($tglKembali) ? (int) $hariIni->diffInDays($tglKembali) : 0;
        $denda = 50000 * $terlambat;

        DB::transaction(function () use ($peminjaman, $terlambat, $denda) {
            Pengembalian::updateOrCreate(
                ['peminjaman_id' => $peminjaman->id],
                [
                    'tgl_dikembalikan' => now()->toDateString(),
                    'terlambat' => $terlambat,
                    'denda' => $denda,
                ]
            );

            $peminjaman->update([
                'status' => 'Dikembalikan',
            ]);

            $peminjaman->detailPeminjaman()->update([
                'status' => 'Dikembalikan',
            ]);

            // Kembalikan / tambahkan stok alat kembali
            $peminjaman->load('detailPeminjaman.alat');
            foreach ($peminjaman->detailPeminjaman as $detail) {
                if ($detail->alat) {
                    $detail->alat->increment('stok', 1);
                }
            }
        });

        $pesanDenda = $terlambat > 0
            ? ' Terlambat ' . $terlambat . ' hari (Denda: Rp ' . number_format($denda, 0, ',', '.') . ').'
            : ' Tepat waktu tanpa denda.';

        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'Pengembalian Alat',
            'keterangan' => 'Pengembalian alat ' . $peminjaman->kode . ' berhasil diproses.' . $pesanDenda,
        ]);

        return back()->with('success', 'Pengembalian alat berhasil diproses dan stok alat telah bertambah kembali.' . $pesanDenda);
    }
}
