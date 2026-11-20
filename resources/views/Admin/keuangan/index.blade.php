@extends('Layout.app')
@section('title', 'Manajemen Keuangan & Arus Kas')
@section('content')
    <main class="space-y-6 pb-12">
        <!-- Top Title & Quick Actions -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-extrabold text-xl text-gray-900 tracking-tight flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-4 h-4"></i>
                    </div>
                    <span>Laporan Keuangan & Arus Kas</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">Pemantauan pendapatan sewa kamera, denda keterlambatan, dan arus kas masuk.</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.keuangan.pdf', request()->query()) }}" target="_blank"
                    class="donezo-btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm"
                    title="Cetak Laporan Keuangan ke PDF (DomPDF)">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Cetak PDF</span>
                </a>

                <a href="{{ route('admin.keuangan.excel', request()->query()) }}"
                    class="donezo-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm"
                    title="Ekspor Laporan Keuangan ke Excel (.xlsx)">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Ekspor Excel</span>
                </a>

                <button type="button" onclick="window.print()" class="donezo-btn donezo-btn-primary text-xs px-3.5 py-2">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Print</span>
                </button>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pemasukan Terealisasi -->
            <div class="donezo-card p-5 border-l-4 border-l-emerald-500 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pemasukan Kas</p>
                    <p class="text-xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Sewa + Denda yang telah terealisasi</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="arrow-down-left" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Total Sewa Terealisasi -->
            <div class="donezo-card p-5 border-l-4 border-l-blue-500 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pendapatan Sewa</p>
                    <p class="text-xl font-extrabold text-blue-600 mt-1">Rp {{ number_format($sewaTerealisasi, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $transaksiSelesaiCount + $transaksiAktifCount }} transaksi sewa berjalan/selesai</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="banknote" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Total Denda Keterlambatan -->
            <div class="donezo-card p-5 border-l-4 border-l-rose-500 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Penerimaan Denda</p>
                    <p class="text-xl font-extrabold text-rose-600 mt-1">Rp {{ number_format($dendaTerealisasi, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Denda keterlambatan pengembalian</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Estimasi Piutang / Pending -->
            <div class="donezo-card p-5 border-l-4 border-l-amber-500 flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Estimasi Pending (Piutang)</p>
                    <p class="text-xl font-extrabold text-amber-600 mt-1">Rp {{ number_format($piutangPending, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5">Peminjaman Menunggu / Disetujui</p>
                </div>
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Breakdown Metode Pembayaran -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="donezo-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center shrink-0">
                    <i data-lucide="coins" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Tunai di Toko</p>
                    <p class="text-base font-extrabold text-gray-900">Rp {{ number_format($metodeTunai, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="donezo-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Transfer Bank</p>
                    <p class="text-base font-extrabold text-gray-900">Rp {{ number_format($metodeTransfer, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="donezo-card p-4 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
                    <i data-lucide="qr-code" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">QRIS Digital</p>
                    <p class="text-base font-extrabold text-gray-900">Rp {{ number_format($metodeQris, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Bar & Data Table -->
        <div class="donezo-card overflow-hidden">
            <!-- Filter Form -->
            <div class="p-5 border-b border-gray-100">
                <form action="{{ route('admin.keuangan.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <!-- Date Filter -->
                        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-3 py-1.5">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400 shrink-0"></i>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Mulai">
                            <span class="text-xs text-gray-400 font-bold">s/d</span>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Selesai">
                        </div>

                        <!-- Status Filter -->
                        <select name="status" class="donezo-input text-xs w-36">
                            <option value="All" {{ request('status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan (Selesai)</option>
                            <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam (Aktif)</option>
                            <option value="Diaprove" {{ request('status') === 'Diaprove' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>

                        <!-- Metode Bayar Filter -->
                        <select name="metode" class="donezo-input text-xs w-36">
                            <option value="All" {{ request('metode') === 'All' ? 'selected' : '' }}>Semua Metode</option>
                            <option value="Tunai" {{ request('metode') === 'Tunai' ? 'selected' : '' }}>Tunai di Toko</option>
                            <option value="Transfer" {{ request('metode') === 'Transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="QRIS" {{ request('metode') === 'QRIS' ? 'selected' : '' }}>QRIS</option>
                        </select>

                        <!-- Search Box -->
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/pelanggan..." class="donezo-input text-xs w-44">

                        <!-- Filter Button -->
                        <button type="submit" class="donezo-btn donezo-btn-primary text-xs px-3 py-2 flex items-center gap-1.5">
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span>Filter</span>
                        </button>

                        <a href="{{ route('admin.keuangan.index') }}" class="donezo-btn donezo-btn-ghost text-xs px-3 py-2" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </a>
                    </div>

                    <!-- Filter Summary Snapshot -->
                    <div class="text-right text-xs text-gray-500">
                        Total Pada Filter: <strong class="text-emerald-700 text-sm">Rp {{ number_format($grandTotalFiltered, 0, ',', '.') }}</strong>
                    </div>
                </form>
            </div>

            <!-- Table of Transactions -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/90 text-[10px] uppercase tracking-wider text-gray-500 font-extrabold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-5 py-3.5 w-36">Kode Transaksi</th>
                            <th class="px-5 py-3.5 w-48">Pelanggan</th>
                            <th class="px-5 py-3.5">Rincian Alat</th>
                            <th class="px-5 py-3.5 w-40">Tgl Sewa</th>
                            <th class="px-5 py-3.5 text-right w-32">Biaya Sewa</th>
                            <th class="px-5 py-3.5 text-right w-28">Denda Telat</th>
                            <th class="px-5 py-3.5 text-right w-36">Total Kas</th>
                            <th class="px-5 py-3.5 w-48">Metode Bayar & Jaminan</th>
                            <th class="px-5 py-3.5 text-center w-28">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($transaksi as $item)
                            @php
                                $denda = $item->pengembalian?->denda ?? 0;
                                $total = ($item->subtotal ?? 0) + $denda;
                                $badgeColor = match($item->status) {
                                    'Dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Dipinjam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Diaprove' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    default => 'bg-rose-50 text-rose-700 border-rose-200',
                                };
                            @endphp
                            <tr class="hover:bg-emerald-50/20 transition">
                                <td class="px-4 py-4 font-mono font-bold text-center text-gray-400">
                                    {{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-gray-900">
                                    <a href="{{ route('invoice.all', $item->id) }}" class="hover:underline text-brand-800">
                                        {{ $item->kode }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    {{ $item->user?->nama ?? '-' }}
                                    <p class="text-[10px] text-gray-400 font-normal">{{ $item->user?->email }}</p>
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    @foreach ($item->detailPeminjaman as $d)
                                        <div class="text-[11px] mb-0.5 last:mb-0">
                                            • <strong>{{ $d->alat?->nama_alat ?? 'Alat' }}</strong>
                                            <span class="text-gray-400 font-mono text-[10px]">({{ $d->lama_pinjam }} Hari)</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-4 text-gray-600 text-[11px]">
                                    <div class="font-medium">{{ $item->tgl_pinjam }}</div>
                                    <div class="text-gray-400 text-[10px]">s/d {{ $item->tgl_kembali }}</div>
                                </td>
                                <td class="px-5 py-4 text-right font-bold text-gray-800">
                                    Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if($denda > 0)
                                        <span class="font-bold text-rose-600">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right font-extrabold text-emerald-700">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-gray-600 max-w-[200px] text-[11px]">
                                    <p class="truncate" title="{{ $item->nama_bukti }}">{{ $item->nama_bukti ?: 'Tunai di Toko' }}</p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-8 text-center text-gray-400">
                                    Tidak ada catatan transaksi keuangan yang sesuai dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $transaksi->links() }}
            </div>
        </div>
    </main>
@endsection
