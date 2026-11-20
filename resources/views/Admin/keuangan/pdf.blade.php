<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Keuangan & Arus Kas - MyCam</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.35;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table {
            margin-bottom: 10px;
        }
        .kop-logo {
            width: 50px;
            height: 50px;
            background-color: #047857;
            color: #ffffff;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            line-height: 50px;
            border-radius: 8px;
        }
        .kop-text {
            padding-left: 12px;
        }
        .kop-title {
            font-size: 16px;
            font-weight: 800;
            color: #064e3b;
            margin: 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .kop-subtitle {
            font-size: 9px;
            color: #475569;
            margin: 2px 0 0 0;
        }
        .kop-divider {
            border-top: 2px solid #047857;
            border-bottom: 1px solid #6ee7b7;
            height: 2px;
            margin: 8px 0 12px 0;
        }
        .report-meta {
            margin-bottom: 12px;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            padding: 6px 10px;
            border-radius: 4px;
        }
        .report-meta td {
            font-size: 9px;
            color: #166534;
            padding: 2px 4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }
        .data-table th {
            background-color: #047857;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 6px;
            border: 1px solid #047857;
            text-align: left;
        }
        .data-table td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
        }
        .badge-lunas { background-color: #d1fae5; color: #065f46; }
        .badge-dp { background-color: #dbeafe; color: #1e40af; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }
        .badge-batal { background-color: #fee2e2; color: #991b1b; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .summary-wrap {
            margin-top: 12px;
            width: 100%;
        }
        .summary-box {
            width: 42%;
            margin-left: auto;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
        }
        .summary-box td {
            padding: 4px 8px;
            font-size: 9px;
        }
        .signature-table {
            width: 100%;
            margin-top: 25px;
        }
        .signature-table td {
            vertical-align: top;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header Kop Surat Keuangan -->
    <table class="header-table">
        <tr>
            <td style="width: 55px; vertical-align: top;">
                <div class="kop-logo">MC</div>
            </td>
            <td class="kop-text" style="vertical-align: top;">
                <h1 class="kop-title">DIVISI KEUANGAN & PERBENDAHARAAN MY-CAM</h1>
                <p class="kop-subtitle">Laporan Arus Kas, Pendapatan Sewa Alat & Penerimaan Denda Keterlambatan</p>
                <p class="kop-subtitle">Jl. Ringroad Selatan No. 88, Yogyakarta | Telepon: (0274) 889977</p>
            </td>
            <td style="width: 150px; text-align: right; vertical-align: top;">
                <p style="font-size: 11px; font-weight: bold; color: #047857; margin: 0;">FINANCIAL REPORT</p>
                <p style="font-size: 8px; color: #64748b; margin: 2px 0 0 0;">Cetak: {{ now()->translatedFormat('d F Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <div class="kop-divider"></div>

    <div style="text-align: center; margin-bottom: 8px;">
        <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; margin: 0; color: #0f172a;">
            LAPORAN KEUANGAN DAN ARUS KAS MASUK
        </h2>
    </div>

    <!-- Parameter Filter Info -->
    <table class="report-meta">
        <tr>
            <td style="width: 15%;"><strong>Periode:</strong></td>
            <td style="width: 35%;">{{ $filter['tanggal_mulai'] ?? 'Awal' }} s/d {{ $filter['tanggal_selesai'] ?? 'Sekarang' }}</td>
            <td style="width: 15%;"><strong>Status Filter:</strong></td>
            <td style="width: 35%;">{{ $filter['status'] === 'All' ? 'Semua Status' : $filter['status'] }}</td>
        </tr>
        <tr>
            <td><strong>Metode Bayar:</strong></td>
            <td>{{ $filter['metode'] === 'All' ? 'Semua Metode Pembayaran' : $filter['metode'] }}</td>
            <td><strong>Total Transaksi:</strong></td>
            <td>{{ $transaksi->count() }} Data Transaksi Tercatat</td>
        </tr>
    </table>

    <!-- Data Table Transaksi Keuangan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th style="width: 80px;">Kode Transaksi</th>
                <th style="width: 110px;">Pelanggan</th>
                <th>Rincian Alat & Waktu Sewa</th>
                <th style="width: 120px;">Metode & Status Bayar</th>
                <th style="width: 75px;" class="text-right">Sewa Alat</th>
                <th style="width: 65px;" class="text-right">Denda Telat</th>
                <th style="width: 80px;" class="text-right">Total Kas</th>
                <th style="width: 65px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $item)
                @php
                    $dendaItem = $item->pengembalian?->denda ?? 0;
                    $totalItem = ($item->subtotal ?? 0) + $dendaItem;
                    $badgeClass = match($item->status) {
                        'Dikembalikan' => 'badge-lunas',
                        'Dipinjam' => 'badge-dp',
                        'Diaprove', 'Menunggu' => 'badge-pending',
                        default => 'badge-batal',
                    };
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $item->kode }}</td>
                    <td>
                        <strong>{{ $item->user?->nama ?? '-' }}</strong>
                        <div style="font-size: 7.5px; color: #64748b;">{{ $item->user?->email ?? '-' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: bold; margin-bottom: 2px;">
                            {{ $item->detailPeminjaman->pluck('alat.nama_alat')->filter()->implode(', ') ?: 'Peralatan Kamera' }}
                        </div>
                        <div style="font-size: 7.5px; color: #64748b;">
                            {{ $item->tgl_pinjam }} s/d {{ $item->tgl_kembali }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size: 8px; color: #334155;">
                            {{ Str::limit($item->nama_bukti ?? 'Tunai di Toko', 50) }}
                        </div>
                    </td>
                    <td class="text-right">Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">
                        @if($dendaItem > 0)
                            <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($dendaItem, 0, ',', '.') }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right font-bold" style="color: #047857;">Rp {{ number_format($totalItem, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $badgeClass }}">{{ $item->status }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px; color: #94a3b8;">
                        Tidak ada catatan transaksi keuangan pada filter yang ditentukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Akumulasi Finansial -->
    <table class="summary-wrap">
        <tr>
            <td style="width: 55%; vertical-align: top;">
                <p style="font-size: 8.5px; color: #64748b; margin: 0;">
                    <em>Rekapitulasi ini merefleksikan seluruh pemasukan biaya sewa harian dan penerimaan denda keterlambatan dari transaksi rental MyCam.</em>
                </p>
            </td>
            <td style="width: 45%; vertical-align: top;">
                <table class="summary-box">
                    <tr>
                        <td><strong>Total Pendapatan Sewa:</strong></td>
                        <td class="text-right">Rp {{ number_format($subtotalSewa, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Total Denda Keterlambatan:</strong></td>
                        <td class="text-right" style="color: #dc2626;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #cbd5e1; background-color: #d1fae5;">
                        <td><strong>TOTAL ARUS KAS MASUK:</strong></td>
                        <td class="text-right" style="font-weight: bold; color: #047857;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan Pengesahan Keuangan -->
    <table class="signature-table">
        <tr>
            <td style="width: 33%;">
                <p>Mengetahui,</p>
                <p><strong>Manager Keuangan</strong></p>
                <div style="height: 50px;"></div>
                <p><u>( ......................................... )</u></p>
                <p>ID: FIN-MGR-001</p>
            </td>
            <td style="width: 33%;"></td>
            <td style="width: 34%;">
                <p>Yogyakarta, {{ now()->translatedFormat('d F Y') }}</p>
                <p><strong>Administrator Sistem</strong></p>
                <div style="height: 50px;"></div>
                <p><u>( {{ Auth::user()->nama ?? 'Admin MyCam' }} )</u></p>
                <p>Email: {{ Auth::user()->email ?? 'admin@mycam.com' }}</p>
            </td>
        </tr>
    </table>

</body>
</html>
