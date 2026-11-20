@extends('Layout.app')
@section('title', 'Riwayat Semua Peminjaman')
@section('content')
    <main class="space-y-6 pb-12">
        <div class="donezo-card overflow-hidden">
            <div class="no-print p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Semua Peminjaman</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Seluruh arsip transaksi sewa dari semua status.</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <form action="{{ route('peminjaman.all') }}" method="GET" class="flex items-center gap-2 flex-wrap">
                        <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-2xl px-2.5 py-1">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">Tgl:</span>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Mulai">
                            <span class="text-[10px] text-gray-400">s/d</span>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Selesai">
                        </div>

                        <select name="status" class="donezo-input text-xs w-36">
                            <option value="All" {{ request('status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diaprove" {{ request('status') === 'Diaprove' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="donezo-input text-xs w-36">
                        
                        <div class="mycam-tooltip-container">
                            <button type="submit" class="donezo-btn donezo-btn-primary p-2" title="Filter Data">
                                <i data-lucide="filter" class="w-4 h-4"></i>
                            </button>
                            <span class="mycam-tooltip-box">Filter</span>
                        </div>
                    </form>

                    <div class="mycam-tooltip-container">
                        <a href="{{ route('peminjaman.all') }}" class="donezo-btn donezo-btn-ghost p-2" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </a>
                        <span class="mycam-tooltip-box">Reset</span>
                    </div>

                    <!-- Tombol Ekspor PDF (DomPDF) -->
                    <div class="mycam-tooltip-container">
                        <a href="{{ route('peminjaman.laporan.pdf', request()->query()) }}" target="_blank"
                            class="donezo-btn bg-rose-600 hover:bg-rose-700 text-white p-2 flex items-center gap-1.5 shadow-sm" title="Unduh PDF (DomPDF)">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span class="text-xs font-semibold hidden lg:inline">PDF</span>
                        </a>
                        <span class="mycam-tooltip-box">Cetak DomPDF</span>
                    </div>

                    <!-- Tombol Ekspor Excel (PhpSpreadsheet) -->
                    <div class="mycam-tooltip-container">
                        <a href="{{ route('peminjaman.laporan.excel', request()->query()) }}"
                            class="donezo-btn bg-emerald-600 hover:bg-emerald-700 text-white p-2 flex items-center gap-1.5 shadow-sm" title="Unduh Excel (.xlsx)">
                            <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                            <span class="text-xs font-semibold hidden lg:inline">Excel</span>
                        </a>
                        <span class="mycam-tooltip-box">Ekspor Excel</span>
                    </div>

                    <div class="mycam-tooltip-container">
                        <button type="button" onclick="window.print()" class="donezo-btn donezo-btn-primary p-2" title="Cetak Riwayat">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            <span class="hidden sm:inline ml-1 text-xs">Print</span>
                        </button>
                        <span class="mycam-tooltip-box">Print Browser</span>
                    </div>
                </div>
            </div>

            <!-- Header Khusus Print -->
            <div class="hidden print:block p-6 text-center border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Laporan Riwayat Peminjaman Alat - MyCam</h2>
                <p class="text-xs text-gray-500 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Pelanggan</th>
                            <th class="px-5 py-3">Alat & Durasi</th>
                            <th class="px-5 py-3">Tgl Pinjam / Kembali</th>
                            <th class="px-5 py-3">Total Bayar</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($peminjaman as $l)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-brand-800">{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
                                <td class="px-5 py-3.5 font-mono font-bold text-gray-900">
                                    {{ $l->kode }}
                                    @if($l->nama_bukti)
                                        <p class="text-[10px] text-gray-500 font-sans font-normal mt-0.5 max-w-[150px] truncate" title="{{ $l->nama_bukti }}">
                                            {{ $l->nama_bukti }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-gray-900">
                                    {{ $l->user?->nama ?? 'Pengguna' }}
                                    <p class="text-[10px] text-gray-400 font-normal">{{ $l->user?->email }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-gray-700">
                                    @foreach ($l->detailPeminjaman as $detail)
                                        <div class="flex items-center gap-1.5 mb-1 last:mb-0">
                                            <span class="font-bold text-gray-800">{{ $detail->alat?->nama_alat ?? 'Alat' }}</span>
                                            <span class="text-[10px] text-gray-500 bg-gray-100 px-1 py-0.5 rounded">({{ $detail->lama_pinjam }} Hari)</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-[11px]">
                                    <div><span class="text-gray-400">Pinjam:</span> {{ $l->tgl_pinjam }}</div>
                                    <div><span class="text-gray-400">Kembali:</span> {{ $l->tgl_kembali }}</div>
                                    @if($l->pengembalian)
                                        <div class="text-[10px] text-emerald-700 mt-0.5">Dikembalikan: {{ $l->pengembalian->tgl_dikembalikan }}</div>
                                        @if($l->pengembalian->terlambat > 0)
                                            <div class="text-[10px] text-rose-600 font-bold">Telat: {{ $l->pengembalian->terlambat }} hari (Denda: Rp {{ number_format($l->pengembalian->denda, 0, ',', '.') }})</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-extrabold text-gray-900">
                                    Rp {{ number_format($l->subtotal ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $badgeColor = match($l->status) {
                                            'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Diaprove' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Dipinjam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                                        {{ $l->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center no-print">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Invoice -->
                                        <div class="mycam-tooltip-container">
                                            <a href="{{ route('invoice.all', $l->id) }}" class="donezo-btn donezo-btn-soft p-2" title="Lihat Invoice">
                                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                            </a>
                                            <span class="mycam-tooltip-box">Invoice</span>
                                        </div>

                                        @if (Auth::user()->role == 'Petugas')
                                            @if ($l->status == 'Menunggu')
                                                <div class="mycam-tooltip-container">
                                                    <button type="button" onclick="openModal('modal-approve-{{ $l->id }}')"
                                                        class="donezo-btn donezo-btn-primary p-2" title="Setujui Peminjaman">
                                                        <i data-lucide="check" class="w-4 h-4"></i>
                                                    </button>
                                                    <span class="mycam-tooltip-box">Setujui</span>
                                                </div>

                                                <div class="mycam-tooltip-container">
                                                    <button type="button" onclick="openModal('modal-batal-{{ $l->id }}')"
                                                        class="donezo-btn donezo-btn-danger p-2" title="Batalkan Peminjaman">
                                                        <i data-lucide="x" class="w-4 h-4"></i>
                                                    </button>
                                                    <span class="mycam-tooltip-box">Batalkan</span>
                                                </div>
                                            @endif

                                            @if ($l->status == 'Diaprove')
                                                <div class="mycam-tooltip-container">
                                                    <button type="button" onclick="openModal('modal-dipinjam-{{ $l->id }}')"
                                                        class="donezo-btn donezo-btn-primary p-2" title="Serahkan Alat">
                                                        <i data-lucide="camera" class="w-4 h-4"></i>
                                                    </button>
                                                    <span class="mycam-tooltip-box">Serahkan Alat</span>
                                                </div>
                                            @endif

                                            @if ($l->status == 'Dipinjam')
                                                <div class="mycam-tooltip-container">
                                                    <button type="button" onclick="openModal('modal-kembali-{{ $l->id }}')"
                                                        class="donezo-btn bg-emerald-700 hover:bg-emerald-800 text-white p-2" title="Proses Pengembalian">
                                                        <i data-lucide="package-check" class="w-4 h-4"></i>
                                                    </button>
                                                    <span class="mycam-tooltip-box">Kembalikan</span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-gray-400">
                                    Belum ada catatan transaksi peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 no-print">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </main>

    <!-- MODAL POPUPS (Diletakkan di luar tag table) -->
    @if (Auth::user()->role == 'Petugas')
        @foreach ($peminjaman as $l)
            @if ($l->status == 'Menunggu')
                <div id="modal-approve-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-approve-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="check" class="w-7 h-7 text-blue-600"></i>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Setujui Peminjaman?</h3>
                        <p class="text-xs text-gray-500 mb-5">Kode: <strong>{{ $l->kode }}</strong> ({{ $l->user?->nama }}).</p>
                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal('modal-approve-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                            <a href="{{ route('approve', $l->id) }}" class="donezo-btn donezo-btn-primary flex-1">Ya, Setujui</a>
                        </div>
                    </div>
                </div>

                <div id="modal-batal-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-batal-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                        <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Batalkan Peminjaman?</h3>
                        <p class="text-xs text-gray-500 mb-5">Kode: <strong>{{ $l->kode }}</strong>. Stok alat akan otomatis dikembalikan.</p>
                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal('modal-batal-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                            <a href="{{ route('batal', $l->id) }}" class="donezo-btn donezo-btn-danger flex-1">Ya, Batalkan</a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($l->status == 'Diaprove')
                <div id="modal-dipinjam-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-dipinjam-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95">
                        <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                            <h3 class="font-bold text-sm text-gray-900">Serahkan Alat (Mulai Pinjam)</h3>
                            <button type="button" onclick="closeModal('modal-dipinjam-{{ $l->id }}')" class="text-gray-400 hover:text-gray-700 p-1">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <form action="{{ route('pinjam', $l->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Bukti Jaminan / Dokumen Pelanggan <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_bukti" value="{{ old('nama_bukti', $l->nama_bukti) }}" class="donezo-input" placeholder="Contoh: KTP Asli - Budi Santoso" required>
                            </div>
                            <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                                <button type="button" onclick="closeModal('modal-dipinjam-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost">Batal</button>
                                <button type="submit" class="donezo-btn donezo-btn-primary">Konfirmasi Penyerahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if ($l->status == 'Dipinjam')
                <div id="modal-kembali-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-kembali-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                        <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="package-check" class="w-7 h-7 text-emerald-600"></i>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Proses Pengembalian Alat?</h3>
                        <p class="text-xs text-gray-500 mb-4">Kode: <strong>{{ $l->kode }}</strong></p>
                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal('modal-kembali-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                            <a href="{{ route('kembalikan', $l->id) }}" class="donezo-btn donezo-btn-primary flex-1">Ya, Selesai</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif

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
