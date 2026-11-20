<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KeuanganController extends Controller
{
    /**
     * Query filter helper for financial records.
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

        if ($request->filled('metode') && $request->metode !== 'All') {
            $query->where('nama_bukti', 'like', "%{$request->metode}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('nama', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhere('nama_bukti', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Display financial management dashboard & transaction journal.
     */
    public function index(Request $request)
    {
        $filteredQuery = $this->filterQuery($request);
        $transaksi = (clone $filteredQuery)->latest()->paginate(15)->withQueryString();

        // Rekap Keseluruhan (Realized & Pending)
        $sewaTerealisasi = Peminjaman::whereIn('status', ['Dipinjam', 'Dikembalikan'])->sum('subtotal');
        $dendaTerealisasi = \App\Models\Pengembalian::sum('denda');
        $totalPemasukan = $sewaTerealisasi + $dendaTerealisasi;

        $piutangPending = Peminjaman::whereIn('status', ['Menunggu', 'Diaprove'])->sum('subtotal');
        $transaksiSelesaiCount = Peminjaman::where('status', 'Dikembalikan')->count();
        $transaksiAktifCount = Peminjaman::where('status', 'Dipinjam')->count();

        // Breakdown Metode Pembayaran
        $metodeTunai = Peminjaman::where('nama_bukti', 'like', '%Tunai%')->sum('subtotal');
        $metodeTransfer = Peminjaman::where('nama_bukti', 'like', '%Transfer%')->sum('subtotal');
        $metodeQris = Peminjaman::where('nama_bukti', 'like', '%QRIS%')->sum('subtotal');

        // Total filtered snapshot
        $allFiltered = (clone $filteredQuery)->get();
        $subtotalFiltered = $allFiltered->sum('subtotal');
        $dendaFiltered = $allFiltered->sum(fn($p) => $p->pengembalian?->denda ?? 0);
        $grandTotalFiltered = $subtotalFiltered + $dendaFiltered;

        return view('Admin.keuangan.index', compact(
            'transaksi',
            'totalPemasukan',
            'sewaTerealisasi',
            'dendaTerealisasi',
            'piutangPending',
            'transaksiSelesaiCount',
            'transaksiAktifCount',
            'metodeTunai',
            'metodeTransfer',
            'metodeQris',
            'subtotalFiltered',
            'dendaFiltered',
            'grandTotalFiltered'
        ));
    }

    /**
     * Export Financial Report to PDF via DomPDF.
     */
    public function exportPdf(Request $request)
    {
        $query = $this->filterQuery($request);
        $transaksi = $query->latest()->get();

        $subtotalSewa = $transaksi->sum('subtotal');
        $totalDenda = $transaksi->sum(fn($p) => $p->pengembalian?->denda ?? 0);
        $grandTotal = $subtotalSewa + $totalDenda;

        $filter = [
            'tanggal_mulai' => $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : null,
            'tanggal_selesai' => $request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : null,
            'status' => $request->status ?: 'All',
            'metode' => $request->metode ?: 'All',
            'search' => $request->search ?: null,
        ];

        $pdf = Pdf::loadView('Admin.keuangan.pdf', compact(
            'transaksi',
            'subtotalSewa',
            'totalDenda',
            'grandTotal',
            'filter'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Keuangan_MyCam_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Export Financial Report to Excel (.xlsx) via PhpSpreadsheet.
     */
    public function exportExcel(Request $request)
    {
        $query = $this->filterQuery($request);
        $transaksi = $query->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Keuangan');

        // Header Dokumen Title
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN DAN ARUS KAS - MY-CAM CAMERA RENTAL');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0F172A');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Subtitle Filter
        $tglAwal = $request->tanggal_mulai ? Carbon::parse($request->tanggal_mulai)->format('d/m/Y') : 'Semua';
        $tglAkhir = $request->tanggal_selesai ? Carbon::parse($request->tanggal_selesai)->format('d/m/Y') : 'Sekarang';
        $statusText = $request->status && $request->status !== 'All' ? $request->status : 'Semua Status';
        $metodeText = $request->metode && $request->metode !== 'All' ? $request->metode : 'Semua Metode';

        $sheet->setCellValue('A2', "Periode: {$tglAwal} s/d {$tglAkhir} | Status: {$statusText} | Metode Bayar: {$metodeText} | Dicetak: " . now()->format('d/m/Y H:i'));
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10)->setColor(new Color('FF475569'));
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Header Kolom
        $headers = [
            'A4' => 'No',
            'B4' => 'Kode Transaksi',
            'C4' => 'Nama Pelanggan',
            'D4' => 'Tgl Pinjam',
            'E4' => 'Tgl Kembali',
            'F4' => 'Daftar Alat',
            'G4' => 'Biaya Sewa (Rp)',
            'H4' => 'Denda Telat (Rp)',
            'I4' => 'Total Diterima (Rp)',
            'J4' => 'Metode Pembayaran & Jaminan',
            'K4' => 'Status Sewa',
            'L4' => 'Keterangan Kas',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        $headerRange = 'A4:L4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new Color(Color::COLOR_WHITE));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF047857');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(26);

        $row = 5;
        $no = 1;
        foreach ($transaksi as $item) {
            $alatList = $item->detailPeminjaman->map(function ($d) {
                return ($d->alat?->nama_alat ?? 'Alat') . ' (' . $d->lama_pinjam . ' Hari)';
            })->implode("\n");

            $biayaSewa = (float) ($item->subtotal ?? 0);
            $denda = (float) ($item->pengembalian?->denda ?? 0);
            $total = $biayaSewa + $denda;

            $statusKas = match($item->status) {
                'Dikembalikan' => 'Masuk Kas (Lunas)',
                'Dipinjam' => 'Masuk Kas / DP Masuk',
                'Diaprove' => 'Booking / Belum Masuk',
                'Menunggu' => 'Pending Review',
                default => 'Batal (Non-Kas)',
            };

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->kode);
            $sheet->setCellValue('C' . $row, $item->user?->nama ?? '-');
            $sheet->setCellValue('D' . $row, $item->tgl_pinjam);
            $sheet->setCellValue('E' . $row, $item->tgl_kembali);
            $sheet->setCellValue('F' . $row, $alatList);
            $sheet->setCellValue('G' . $row, $biayaSewa);
            $sheet->setCellValue('H' . $row, $denda);
            $sheet->setCellValue('I' . $row, $total);
            $sheet->setCellValue('J' . $row, $item->nama_bukti ?? '-');
            $sheet->setCellValue('K' . $row, $item->status);
            $sheet->setCellValue('L' . $row, $statusKas);

            $sheet->getStyle('F' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('J' . $row)->getAlignment()->setWrapText(true);

            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}:E{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("K{$row}:L{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            if ($no % 2 === 0) {
                $sheet->getStyle("A{$row}:L{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF0FDF4');
            }

            $row++;
        }

        $totalRow = $row;
        $sheet->setCellValue('A' . $totalRow, 'TOTAL KEUANGAN');
        $sheet->mergeCells("A{$totalRow}:F{$totalRow}");
        $sheet->setCellValue('G' . $totalRow, "=SUM(G5:G" . ($totalRow - 1) . ")");
        $sheet->setCellValue('H' . $totalRow, "=SUM(H5:H" . ($totalRow - 1) . ")");
        $sheet->setCellValue('I' . $totalRow, "=SUM(I5:I" . ($totalRow - 1) . ")");

        // Summary borders & format
        $dataRange = "A4:L{$totalRow}";
        $sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FFE2E8F0');
        $sheet->getStyle("A{$totalRow}:L{$totalRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$totalRow}:L{$totalRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFD1FAE5');
        $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getRowDimension($totalRow)->setRowHeight(24);

        $sheet->getStyle("G5:I{$totalRow}")->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Laporan_Keuangan_MyCam_' . now()->format('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
