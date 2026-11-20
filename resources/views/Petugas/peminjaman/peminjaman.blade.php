@extends('Layout.app')
@section('title', 'Riwayat Semua Peminjaman')
@section('content')
    <main class="space-y-6 pb-12">
        <!-- Top Title & Quick Actions Header -->
        <div class="no-print flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-800 text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                    <i data-lucide="history" class="w-5 h-5 text-brand-300"></i>
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-gray-900 tracking-tight">Riwayat Semua Peminjaman</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Seluruh arsip data transaksi sewa kamera dan status pengembalian.</p>
                </div>
            </div>

            <!-- Action Buttons: PDF, Excel, Print -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('peminjaman.laporan.pdf', request()->query()) }}" target="_blank"
                    class="donezo-btn bg-rose-600 hover:bg-rose-700 text-white text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm transition"
                    title="Cetak Laporan ke PDF (DomPDF)">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Cetak PDF</span>
                </a>

                <a href="{{ route('peminjaman.laporan.excel', request()->query()) }}"
                    class="donezo-btn bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm transition"
                    title="Ekspor Laporan ke Excel (.xlsx)">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Ekspor Excel</span>
                </a>

                <button type="button" onclick="window.print()"
                    class="donezo-btn donezo-btn-primary text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-sm transition"
                    title="Cetak Tampilan Browser">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Print</span>
                </button>
            </div>
        </div>

        <!-- Filter Card / Search Toolbar -->
        <div class="donezo-card p-4 sm:p-5 no-print">
            <form action="{{ route('peminjaman.all') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                <div class="flex items-center gap-2 flex-wrap flex-1">
                    <!-- Date Range Filter -->
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-3 py-1.5">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400 shrink-0"></i>
                        <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                            class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Mulai">
                        <span class="text-xs text-gray-400 font-bold">s/d</span>
                        <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                            class="bg-transparent text-xs text-gray-700 outline-none" title="Tanggal Selesai">
                    </div>

                    <!-- Status Dropdown Filter -->
                    <select name="status" class="donezo-input text-xs w-36">
                        <option value="All" {{ request('status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="Diaprove" {{ request('status') === 'Diaprove' ? 'selected' : '' }}>Disetujui</option>
                        <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="Dibatalkan" {{ request('status') === 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode, nama pelanggan, atau nama alat..."
                            class="donezo-input text-xs w-full pl-9">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>

                    <!-- Filter & Reset Buttons -->
                    <button type="submit" class="donezo-btn donezo-btn-primary text-xs px-3.5 py-2 flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>

                    <a href="{{ route('peminjaman.all') }}" class="donezo-btn donezo-btn-ghost text-xs p-2" title="Reset Filter">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                </div>

                <!-- Total Snapshot Badge -->
                <div class="text-right shrink-0">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-800 bg-brand-50 px-3 py-1.5 rounded-xl border border-brand-200">
                        <i data-lucide="database" class="w-3.5 h-3.5 text-brand-600"></i>
                        <span>Total: {{ $peminjaman->total() }} Transaksi</span>
                    </span>
                </div>
            </form>
        </div>

        <!-- Main Table Card -->
        <div class="donezo-card overflow-hidden">
            <!-- Header Khusus Print Browser -->
            <div class="hidden print:block p-6 text-center border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Laporan Riwayat Peminjaman Alat - MyCam</h2>
                <p class="text-xs text-gray-500 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/90 text-[10px] uppercase tracking-wider text-gray-500 font-extrabold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-5 py-3.5 w-36">Kode Transaksi</th>
                            <th class="px-5 py-3.5 w-52">Pelanggan</th>
                            <th class="px-5 py-3.5">Alat & Durasi</th>
                            <th class="px-5 py-3.5 w-44">Jadwal Sewa</th>
                            <th class="px-5 py-3.5 text-right w-36">Total Tagihan</th>
                            <th class="px-5 py-3.5 text-center w-28">Status</th>
                            <th class="px-5 py-3.5 text-center w-28 no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($peminjaman as $l)
                            <tr class="hover:bg-brand-50/20 transition">
                                <td class="px-4 py-4 font-mono font-bold text-center text-brand-800">
                                    {{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}
                                </td>
                                <td class="px-5 py-4 font-mono font-bold text-gray-900">
                                    <a href="{{ route('invoice.all', $l->id) }}" class="text-brand-800 hover:underline">
                                        {{ $l->kode }}
                                    </a>
                                    @if($l->nama_bukti)
                                        <p class="text-[10px] text-gray-400 font-sans font-normal mt-0.5 max-w-[150px] truncate" title="{{ $l->nama_bukti }}">
                                            {{ $l->nama_bukti }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 font-semibold text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-xs uppercase shrink-0">
                                            {{ substr($l->user?->nama ?? 'P', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 truncate">{{ $l->user?->nama ?? 'Pengguna' }}</p>
                                            <p class="text-[10px] text-gray-400 font-normal truncate">{{ $l->user?->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-700">
                                    @foreach ($l->detailPeminjaman as $detail)
                                        <div class="flex items-center gap-1.5 mb-1 last:mb-0">
                                            <span class="font-bold text-gray-800">• {{ $detail->alat?->nama_alat ?? 'Alat' }}</span>
                                            <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded font-mono font-semibold">({{ $detail->lama_pinjam }} Hari)</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-4 text-gray-600 text-[11px]">
                                    <div class="flex items-center gap-1 text-gray-700">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Pinjam:</span>
                                        <span>{{ $l->tgl_pinjam }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-gray-700 mt-0.5">
                                        <span class="text-[10px] font-bold text-gray-400 uppercase">Kembali:</span>
                                        <span>{{ $l->tgl_kembali }}</span>
                                    </div>
                                    @if($l->pengembalian)
                                        <div class="text-[10px] text-emerald-700 font-bold mt-1 flex items-center gap-1">
                                            <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                            <span>Dikembalikan: {{ $l->pengembalian->tgl_dikembalikan }}</span>
                                        </div>
                                        @if($l->pengembalian->terlambat > 0)
                                            <div class="text-[10px] text-rose-600 font-bold mt-0.5">
                                                Telat {{ $l->pengembalian->terlambat }} hari (Denda: Rp {{ number_format($l->pengembalian->denda, 0, ',', '.') }})
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="font-extrabold text-sm text-gray-900">
                                        Rp {{ number_format($l->subtotal ?? 0, 0, ',', '.') }}
                                    </p>
                                    @if($l->pengembalian && $l->pengembalian->denda > 0)
                                        <p class="text-[10px] text-rose-600 font-semibold mt-0.5">
                                            + Denda: Rp {{ number_format($l->pengembalian->denda, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $badgeColor = match($l->status) {
                                            'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Diaprove' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Dipinjam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }}">
                                        {{ $l->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center no-print">
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
                                <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                    <p class="font-bold text-gray-600">Belum ada catatan transaksi peminjaman.</p>
                                    <p class="text-xs text-gray-400 mt-1">Coba sesuaikan kata kunci atau rentang tanggal filter Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 no-print flex items-center justify-between">
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

                        <form action="{{ route('approve', $l->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="text-left">
                                <label class="text-[11px] font-bold text-gray-700 block mb-1">Syarat / Jaminan Disetujui</label>
                                <input type="text" name="syarat_jaminan" 
                                    value="{{ $l->nama_bukti ?: 'KTP Asli / Pembayaran DP Terverifikasi' }}" 
                                    class="donezo-input text-xs w-full" required>
                                <p class="text-[10px] text-gray-400 mt-1">Sertakan syarat pengambilan unit kamera.</p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="closeModal('modal-approve-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                                <button type="submit" class="donezo-btn donezo-btn-primary flex-1">Ya, Setujui</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="modal-batal-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-batal-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                        <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Tolak / Batalkan Peminjaman?</h3>
                        <p class="text-xs text-gray-500 mb-5">Pengajuan <strong>{{ $l->kode }}</strong> akan dibatalkan dan seluruh stok alat dikembalikan.</p>
                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal('modal-batal-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Kembali</button>
                            <a href="{{ route('batal', $l->id) }}" class="donezo-btn donezo-btn-danger flex-1">Ya, Batalkan</a>
                        </div>
                    </div>
                </div>
            @endif

            @if ($l->status == 'Diaprove')
                <div id="modal-dipinjam-{{ $l->id }}"
                    class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
                    onclick="if(event.target===this)closeModal('modal-dipinjam-{{ $l->id }}')">
                    <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                        <div class="w-14 h-14 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="camera" class="w-7 h-7 text-brand-800"></i>
                        </div>
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Serahkan Alat (Status: Dipinjam)</h3>
                        <p class="text-xs text-gray-500 mb-4">Unit diserahkan ke pelanggan <strong>{{ $l->user?->nama }}</strong>.</p>
                        
                        <form action="{{ route('pinjam', $l->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="text-left">
                                <label class="text-[11px] font-bold text-gray-700 block mb-1">Bukti Jaminan Fisik Diterima</label>
                                <input type="text" name="nama_bukti" value="{{ $l->nama_bukti }}" placeholder="Contoh: KTP Asli An. Budi / SIM A" class="donezo-input text-xs w-full" required>
                                <p class="text-[10px] text-gray-400 mt-1">Wajib mencatat identitas jaminan yang ditahan.</p>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="closeModal('modal-dipinjam-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                                <button type="submit" class="donezo-btn donezo-btn-primary flex-1">Konfirmasi Serah Alat</button>
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
                        <h3 class="font-bold text-sm text-gray-900 mb-1">Proses Pengembalian Alat</h3>
                        <p class="text-xs text-gray-500 mb-3">Unit kamera <strong>{{ $l->kode }}</strong> telah diterima kembali dalam kondisi baik.</p>
                        
                        @php
                            $tglKembali = \Carbon\Carbon::parse($l->tgl_kembali)->startOfDay();
                            $hariIni = now()->startOfDay();
                            $hariTelat = $hariIni->greaterThan($tglKembali) ? (int) $hariIni->diffInDays($tglKembali) : 0;
                            $estDenda = $hariTelat * 50000;
                        @endphp

                        @if($hariTelat > 0)
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-left mb-4">
                                <p class="text-xs font-bold text-rose-700 flex items-center gap-1">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                    Terlambat {{ $hariTelat }} Hari
                                </p>
                                <p class="text-[11px] text-rose-600 mt-1">Denda keterlambatan: <strong>Rp {{ number_format($estDenda, 0, ',', '.') }}</strong></p>
                            </div>
                        @else
                            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl text-left mb-4">
                                <p class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                    Tepat Waktu
                                </p>
                                <p class="text-[11px] text-emerald-600 mt-0.5">Tidak dikenakan denda keterlambatan.</p>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <button type="button" onclick="closeModal('modal-kembali-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                            <a href="{{ route('kembalikan', $l->id) }}" class="donezo-btn bg-emerald-700 hover:bg-emerald-800 text-white flex-1">Selesaikan Pengembalian</a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
@endsection
