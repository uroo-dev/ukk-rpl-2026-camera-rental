@extends('Layout.app')
@section('title', 'Invoice #' . $peminjaman->kode)
@section('content')
<main class="space-y-6 pb-12 px-5 py-6 max-w-4xl mx-auto">
    <!-- Header Aksi Petugas -->
    <div class="no-print flex items-center justify-between donezo-card p-4 sm:p-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('peminjaman.all') }}" class="donezo-btn donezo-btn-ghost p-2" title="Kembali ke Daftar">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h1 class="font-bold text-base text-gray-900 tracking-tight">Invoice Transaksi Peminjaman</h1>
                <p class="text-[11px] text-gray-400 font-mono">Kode: {{ $peminjaman->kode }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('invoice.pdf', $peminjaman->id) }}" target="_blank"
                class="donezo-btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm"
                title="Unduh Invoice PDF (DomPDF)">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Unduh PDF</span>
            </a>
            <button type="button" onclick="window.print()" class="donezo-btn donezo-btn-primary text-xs px-3.5 py-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Cetak Invoice</span>
            </button>
        </div>
    </div>

    <!-- Container Invoice Kertas -->
    <div class="donezo-card p-8 sm:p-12 bg-white shadow-md border border-gray-100">
        <!-- Invoice Header -->
        <div class="flex flex-col sm:flex-row items-start justify-between gap-6 pb-8 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand-800 text-white flex items-center justify-center shadow-md">
                        <svg class="w-7 h-7 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <p class="font-extrabold text-2xl tracking-tight text-gray-900 leading-none">MyCam</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-brand-600 mt-1">Sistem Peminjaman Kamera</p>
                        @php
                            $badgeColor = match($peminjaman->status) {
                                'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'Diaprove' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'Dipinjam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'Dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                default => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                        @endphp
                        <span class="inline-block text-[10px] font-bold px-2.5 py-0.5 rounded-full border {{ $badgeColor }} mt-1.5">
                            Status: {{ $peminjaman->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight uppercase">INVOICE</p>
                <p class="text-xs font-mono font-bold text-brand-800 mt-1">#{{ $peminjaman->kode }}</p>
                <div class="mt-3 space-y-1 text-xs text-gray-600">
                    <p><span class="text-gray-400">Tgl Pinjam:</span> <strong class="text-gray-800">{{ $peminjaman->tgl_pinjam }}</strong></p>
                    <p><span class="text-gray-400">Tgl Kembali:</span> <strong class="text-gray-800">{{ $peminjaman->tgl_kembali }}</strong></p>
                    @if($peminjaman->nama_bukti)
                        <p><span class="text-gray-400">Jaminan:</span> <strong class="text-gray-800">{{ $peminjaman->nama_bukti }}</strong></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Penyewa & Petugas -->
        <div class="py-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4 text-xs">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Data Pelanggan</p>
                <p class="font-bold text-gray-900 text-sm">{{ $peminjaman->user?->nama }}</p>
                <p class="text-gray-500">{{ $peminjaman->user?->email }}</p>
                @if($peminjaman->user?->pelanggan?->first())
                    <p class="text-gray-500">{{ $peminjaman->user->pelanggan->first()->no_hp }}</p>
                    <p class="text-gray-500 max-w-xs mt-0.5">{{ $peminjaman->user->pelanggan->first()->alamat }}</p>
                @endif
            </div>
            <div class="sm:text-right">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Pengelola / Kasir</p>
                <p class="font-bold text-gray-900 text-sm">Petugas MyCam</p>
                <p class="text-gray-500">Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</p>
            </div>
        </div>

        <!-- Line Items -->
        <div class="py-6">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] uppercase tracking-wider text-gray-400 font-bold border-b border-gray-100">
                        <th class="pb-3">Item / Alat</th>
                        <th class="pb-3 text-center">Durasi</th>
                        <th class="pb-3 text-right">Tarif / Hari</th>
                        <th class="pb-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100">
                    @foreach ($peminjaman->detailPeminjaman as $detail)
                        <tr>
                            <td class="py-3.5">
                                <p class="font-bold text-gray-900">{{ $detail->alat?->nama_alat ?? 'Alat Kamera' }}</p>
                                <p class="text-[10px] text-gray-400">{{ $detail->alat?->kategori?->nama_kategori }}</p>
                            </td>
                            <td class="py-3.5 text-center text-gray-700 font-semibold">{{ $detail->lama_pinjam }} Hari</td>
                            <td class="py-3.5 text-right text-gray-600">Rp {{ number_format($detail->alat?->harga_sewa ?? 0, 0, ',', '.') }}</td>
                            <td class="py-3.5 text-right font-bold text-gray-900">
                                Rp {{ number_format($detail->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Denda -->
        <div class="flex justify-end pb-8 border-b border-gray-100">
            <div class="w-full sm:w-80 space-y-2 text-xs">
                <div class="flex items-center justify-between text-gray-600">
                    <span>Subtotal Biaya Sewa</span>
                    <span class="font-bold text-gray-900">Rp {{ number_format($peminjaman->subtotal, 0, ',', '.') }}</span>
                </div>

                @if($peminjaman->pengembalian && $peminjaman->pengembalian->denda > 0)
                    <div class="flex items-center justify-between text-rose-600 font-semibold">
                        <span>Denda Telat ({{ $peminjaman->pengembalian->terlambat }} Hari)</span>
                        <span>+ Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-sm font-bold text-gray-900">
                    <span>Grand Total</span>
                    <span class="text-xl font-extrabold text-brand-800">
                        Rp {{ number_format($peminjaman->subtotal + ($peminjaman->pengembalian?->denda ?? 0), 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Notes & Signature -->
        <div class="pt-8 flex flex-col sm:flex-row items-start justify-between gap-6">
            <div class="space-y-1 max-w-sm text-[11px] text-gray-500">
                <p class="font-bold uppercase tracking-wider text-gray-400 text-[10px]">Catatan</p>
                <p class="leading-relaxed">Dokumen ini merupakan bukti resmi penerimaan & transaksi sewa alat kamera di MyCam.</p>
            </div>
            <div class="flex gap-8 text-center sm:text-right">
                <div>
                    <p class="text-[11px] font-bold text-gray-900 border-b border-gray-300 pb-1 px-4 inline-block">
                        {{ $peminjaman->user?->nama }}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-1">Pelanggan</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-900 border-b border-gray-300 pb-1 px-4 inline-block">
                        {{ Auth::user()->nama }}
                    </p>
                    <p class="text-[10px] text-gray-400 mt-1">Petugas / Kasir</p>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
    }
</style>
@endsection
