<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $peminjaman->kode }} - MyCam</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-box {
            width: 44px;
            height: 44px;
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            line-height: 44px;
            border-radius: 6px;
        }
        .brand-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .brand-sub {
            font-size: 8.5px;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            margin: 2px 0 0 0;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 1px;
            margin: 0;
            text-align: right;
        }
        .invoice-code {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            text-align: right;
            margin: 2px 0 0 0;
        }
        .divider {
            border-top: 2px solid #1e3a8a;
            margin: 12px 0 15px 0;
        }
        .info-table td {
            vertical-align: top;
            padding: 4px;
            font-size: 9.5px;
        }
        .section-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .data-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 7px 8px;
            border: 1px solid #1e3a8a;
            text-align: left;
        }
        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 9.5px;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-status {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        .calc-table {
            width: 45%;
            margin-left: auto;
            margin-top: 12px;
            border-collapse: collapse;
        }
        .calc-table td {
            padding: 4px 8px;
            font-size: 9.5px;
        }
        .grand-total-row {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
        }
        .grand-total-row td {
            padding: 7px 8px;
            color: #ffffff;
        }
        .footer-table {
            width: 100%;
            margin-top: 30px;
        }
        .footer-table td {
            vertical-align: top;
            font-size: 9px;
        }
    </style>
</head>
<body>

    <!-- Header Invoice -->
    <table class="header-table">
        <tr>
            <td style="width: 50px;">
                <div class="logo-box">MC</div>
            </td>
            <td style="padding-left: 10px;">
                <p class="brand-title">MyCam Rental</p>
                <p class="brand-sub">Sistem Peminjaman Kamera & Multimedia</p>
                <p style="font-size: 8.5px; color: #64748b; margin: 2px 0 0 0;">Jl. Ringroad Selatan No. 88, Yogyakarta | (0274) 889977</p>
            </td>
            <td style="text-align: right;">
                <h1 class="invoice-title">INVOICE</h1>
                <p class="invoice-code">#{{ $peminjaman->kode }}</p>
                <div style="margin-top: 4px;">
                    <span class="badge badge-status">Status: {{ $peminjaman->status }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Customer & Loan Info Box -->
    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <div class="section-box">
                    <p class="section-title">Informasi Penyewa / Pelanggan</p>
                    <p style="font-size: 11px; font-weight: bold; margin: 0 0 2px 0;">{{ $peminjaman->user?->nama ?? 'Pengguna' }}</p>
                    <p style="margin: 0; color: #475569;">Email: {{ $peminjaman->user?->email ?? '-' }}</p>
                    <p style="margin: 0; color: #475569;">No. HP: {{ $peminjaman->user?->pelanggan?->first()?->no_hp ?? '-' }}</p>
                    <p style="margin: 0; color: #475569;">Alamat: {{ $peminjaman->user?->pelanggan?->first()?->alamat ?? '-' }}</p>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="section-box">
                    <p class="section-title">Detail Waktu & Ketentuan Sewa</p>
                    <p style="margin: 0;"><strong>Tgl Pinjam:</strong> {{ $peminjaman->tgl_pinjam }}</p>
                    <p style="margin: 0;"><strong>Tgl Kembali:</strong> {{ $peminjaman->tgl_kembali }} ({{ $lama }} Hari)</p>
                    @if($peminjaman->nama_bukti)
                        <p style="margin: 2px 0 0 0; color: #1e3a8a; font-size: 8.5px;"><strong>Jaminan & Bayar:</strong> {{ $peminjaman->nama_bukti }}</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Rincian Alat Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px;" class="text-center">No</th>
                <th>Nama Peralatan</th>
                <th style="width: 100px;">Kategori</th>
                <th style="width: 80px;" class="text-right">Harga Sewa / Hari</th>
                <th style="width: 70px;" class="text-center">Durasi</th>
                <th style="width: 90px;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($peminjaman->detailPeminjaman as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="font-bold">{{ $item->alat?->nama_alat ?? 'Alat Kamera' }}</td>
                    <td>{{ $item->alat?->kategori?->nama_kategori ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->alat?->harga_sewa ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->lama_pinjam }} Hari</td>
                    <td class="text-right font-bold">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Calculation Summary -->
    <table class="calc-table">
        <tr>
            <td><strong>Subtotal Biaya Sewa:</strong></td>
            <td class="text-right font-bold">Rp {{ number_format($peminjaman->subtotal ?? 0, 0, ',', '.') }}</td>
        </tr>
        @if ($peminjaman->pengembalian)
            <tr>
                <td>Keterlambatan:</td>
                <td class="text-right">{{ $peminjaman->pengembalian->terlambat }} Hari</td>
            </tr>
            <tr>
                <td>Denda Keterlambatan:</td>
                <td class="text-right" style="color: #dc2626; font-weight: bold;">
                    Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}
                </td>
            </tr>
        @endif
        <tr class="grand-total-row">
            <td>TOTAL TAGIHAN:</td>
            <td class="text-right">
                Rp {{ number_format(($peminjaman->subtotal ?? 0) + ($peminjaman->pengembalian?->denda ?? 0), 0, ',', '.') }}
            </td>
        </tr>
    </table>

    <!-- Terms & Signature -->
    <table class="footer-table">
        <tr>
            <td style="width: 60%;">
                <p style="font-weight: bold; margin: 0 0 3px 0; color: #1e3a8a;">Syarat & Ketentuan Peminjaman:</p>
                <ol style="margin: 0; padding-left: 15px; font-size: 8px; color: #64748b; line-height: 1.4;">
                    <li>Penyewa wajib menjaga kebersihan dan kelengkapan unit alat selama masa sewa.</li>
                    <li>Keterlambatan pengembalian tanpa konfirmasi dikenakan denda Rp 50.000 / hari.</li>
                    <li>Kerusakan atau kehilangan unit menjadi tanggung jawab penuh pihak penyewa.</li>
                </ol>
            </td>
            <td style="width: 40%; text-align: center;">
                <p style="margin: 0;">Yogyakarta, {{ now()->translatedFormat('d F Y') }}</p>
                <p style="margin: 2px 0 0 0; font-weight: bold;">Petugas Operasional,</p>
                <div style="height: 45px;"></div>
                <p style="margin: 0;"><u>( MyCam Rental Services )</u></p>
            </td>
        </tr>
    </table>

</body>
</html>
