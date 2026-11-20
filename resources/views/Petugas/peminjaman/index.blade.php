@extends('Layout.app')
@section('title', 'Peminjaman Aktif')
@section('content')
    <main class="space-y-6 pb-12">
        <div class="donezo-card overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Peminjaman Aktif</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Kelola persetujuan, penyerahan alat, dan pengembalian.</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <form action="{{ route('peminjaman') }}" method="GET" class="flex items-center gap-2">
                        <select name="status" class="donezo-input text-xs w-36" onchange="this.form.submit()">
                            <option value="All" {{ request('status') === 'All' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Menunggu" {{ request('status') === 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Diaprove" {{ request('status') === 'Diaprove' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Dipinjam" {{ request('status') === 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        </select>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="donezo-input text-xs w-40">
                        <div class="mycam-tooltip-container">
                            <button type="submit" class="donezo-btn donezo-btn-primary p-2" title="Filter Data">
                                <i data-lucide="filter" class="w-4 h-4"></i>
                            </button>
                            <span class="mycam-tooltip-box">Filter</span>
                        </div>
                    </form>

                    <div class="mycam-tooltip-container">
                        <a href="{{ route('peminjaman') }}" class="donezo-btn donezo-btn-ghost p-2" title="Reset Filter">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        </a>
                        <span class="mycam-tooltip-box">Reset</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Pelanggan</th>
                            <th class="px-5 py-3">Alat & Durasi</th>
                            <th class="px-5 py-3">Tgl Pinjam / Kembali</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Aksi Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($peminjaman as $l)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-brand-800">
                                    {{ $l->kode }}
                                    @if($l->nama_bukti)
                                        <p class="text-[10px] text-gray-400 font-sans font-normal truncate max-w-[140px]" title="Jaminan: {{ $l->nama_bukti }}">
                                            Bukti: {{ $l->nama_bukti }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-gray-900">
                                    {{ $l->user?->nama ?? 'Pengguna' }}
                                    <p class="text-[10px] text-gray-400 font-normal">{{ $l->user?->email }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-gray-700">
                                    @foreach ($l->detailPeminjaman as $detail)
                                        <div class="flex items-center gap-2 mb-1 last:mb-0">
                                            <span class="font-bold text-gray-800">{{ $detail->alat?->nama_alat ?? 'Alat' }}</span>
                                            <span class="text-[10px] text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">({{ $detail->lama_pinjam }} Hari)</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-[11px]">
                                    <div><span class="text-gray-400">Pinjam:</span> {{ $l->tgl_pinjam }}</div>
                                    <div><span class="text-gray-400">Kembali:</span> {{ $l->tgl_kembali }}</div>
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
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Invoice -->
                                        <div class="mycam-tooltip-container">
                                            <a href="{{ route('invoice.all', $l->id) }}" class="donezo-btn donezo-btn-soft p-2" title="Lihat Invoice">
                                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                            </a>
                                            <span class="mycam-tooltip-box">Invoice</span>
                                        </div>

                                        <!-- Aksi jika Status: Menunggu -->
                                        @if ($l->status == 'Menunggu')
                                            <!-- Approve -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-approve-{{ $l->id }}')"
                                                    class="donezo-btn donezo-btn-primary p-2" title="Setujui Peminjaman">
                                                    <i data-lucide="check" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Setujui</span>
                                            </div>

                                            <!-- Tolak/Batal -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-batal-{{ $l->id }}')"
                                                    class="donezo-btn donezo-btn-danger p-2" title="Tolak & Batalkan">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Batalkan</span>
                                            </div>
                                        @endif

                                        <!-- Aksi jika Status: Diaprove (Disetujui) -> Serahkan Alat -->
                                        @if ($l->status == 'Diaprove')
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-dipinjam-{{ $l->id }}')"
                                                    class="donezo-btn donezo-btn-primary p-2" title="Serahkan Alat (Mulai Pinjam)">
                                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Serahkan Alat</span>
                                            </div>

                                            <!-- Opsi Batal jika pelanggan membatalkan sebelum serah terima -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-batal-{{ $l->id }}')"
                                                    class="donezo-btn donezo-btn-danger p-2" title="Batalkan Peminjaman">
                                                    <i data-lucide="x" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Batalkan</span>
                                            </div>
                                        @endif

                                        <!-- Aksi jika Status: Dipinjam -> Kembalikan Alat -->
                                        @if ($l->status == 'Dipinjam')
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-kembali-{{ $l->id }}')"
                                                    class="donezo-btn bg-emerald-700 hover:bg-emerald-800 text-white p-2" title="Proses Pengembalian">
                                                    <i data-lucide="package-check" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Kembalikan</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                                    Tidak ada data peminjaman aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $peminjaman->links() }}
            </div>
        </div>
    </main>

    <!-- MODAL POPUPS (Diletakkan di luar tag table agar valid HTML) -->
    @foreach ($peminjaman as $l)
        <!-- Modal 1: Approve Peminjaman dengan Syarat Jaminan / Pembayaran (UKK Tantangan 2) -->
        <div id="modal-approve-{{ $l->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-approve-{{ $l->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="check" class="w-6 h-6 text-blue-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Setujui Peminjaman?</h3>
                <p class="text-xs text-gray-500 mb-4">
                    Peminjam: <strong>{{ $l->user?->nama }}</strong> ({{ $l->kode }})
                </p>

                <form action="{{ route('approve', $l->id) }}" method="POST" class="text-left space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Syarat Jaminan / Pembayaran <span class="text-rose-500">*</span></label>
                        <input type="text" name="syarat_jaminan" required value="{{ $l->nama_bukti ?: 'KTP Asli / Pembayaran Lunas' }}"
                            placeholder="Contoh: KTP No. 3201... atau Bukti Bayar Lunas" class="donezo-input text-xs w-full">
                        <p class="text-[10px] text-gray-400 mt-1">Syarat persetujuan (contoh: jaminan identitas KTP/SIM atau bukti pembayaran).</p>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="closeModal('modal-approve-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">
                            Batal
                        </button>
                        <button type="submit" class="donezo-btn donezo-btn-primary flex-1">
                            Ya, Setujui
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 2: Batal / Tolak Peminjaman -->
        <div id="modal-batal-{{ $l->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-batal-{{ $l->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Batalkan Peminjaman?</h3>
                <p class="text-xs text-gray-500 mb-5">
                    Peminjaman <strong>{{ $l->kode }}</strong> akan dibatalkan dan stok alat akan dikembalikan otomatis ke inventaris.
                </p>
                <div class="flex gap-2">
                    <button type="button" onclick="closeModal('modal-batal-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">
                        Batal
                    </button>
                    <a href="{{ route('batal', $l->id) }}" class="donezo-btn donezo-btn-danger flex-1">
                        Ya, Batalkan
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal 3: Konfirmasi Serah Terima Alat (Dipinjam) -->
        <div id="modal-dipinjam-{{ $l->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-dipinjam-{{ $l->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-sm text-gray-900">Penyerahan Alat (Mulai Peminjaman)</h3>
                    <button type="button" onclick="closeModal('modal-dipinjam-{{ $l->id }}')" class="text-gray-400 hover:text-gray-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('pinjam', $l->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="p-3.5 rounded-2xl bg-gray-50 text-xs space-y-1">
                        <p><span class="text-gray-500">Kode:</span> <strong>{{ $l->kode }}</strong></p>
                        <p><span class="text-gray-500">Peminjam:</span> <strong>{{ $l->user?->nama }}</strong></p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Bukti Jaminan / Identitas <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_bukti" value="{{ old('nama_bukti', $l->nama_bukti) }}" class="donezo-input" placeholder="Contoh: KTP Asli - Budi Santoso / SIM A" required>
                        <p class="text-[10px] text-gray-400 mt-1">Masukkan jenis identitas atau dokumen yang dijaminkan oleh pelanggan saat serah terima alat.</p>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-dipinjam-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost">
                            Batal
                        </button>
                        <button type="submit" class="donezo-btn donezo-btn-primary">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Konfirmasi Penyerahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal 4: Konfirmasi Pengembalian Alat -->
        <div id="modal-kembali-{{ $l->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-kembali-{{ $l->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package-check" class="w-7 h-7 text-emerald-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Proses Pengembalian Alat?</h3>
                <p class="text-xs text-gray-500 mb-3">
                    Kode: <strong>{{ $l->kode }}</strong>. Pastikan kondisi kamera dan kelengkapannya sudah diperiksa dengan baik.
                </p>
                @php
                    $tglRencana = \Carbon\Carbon::parse($l->tgl_kembali)->startOfDay();
                    $hariIni = now()->startOfDay();
                    $terlambatHari = $hariIni->greaterThan($tglRencana) ? (int) $hariIni->diffInDays($tglRencana) : 0;
                    $estimasiDenda = 50000 * $terlambatHari;
                @endphp
                @if($terlambatHari > 0)
                    <div class="p-3 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs text-left">
                        <p class="font-bold">Keterlambatan: {{ $terlambatHari }} Hari</p>
                        <p class="text-[11px]">Denda keterlambatan: <strong>Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</strong></p>
                    </div>
                @else
                    <div class="p-2.5 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                        Pengembalian tepat waktu (Tidak ada denda).
                    </div>
                @endif
                <div class="flex gap-2">
                    <button type="button" onclick="closeModal('modal-kembali-{{ $l->id }}')" class="donezo-btn donezo-btn-ghost flex-1">
                        Batal
                    </button>
                    <a href="{{ route('kembalikan', $l->id) }}" class="donezo-btn donezo-btn-primary flex-1">
                        Ya, Selesai
                    </a>
                </div>
            </div>
        </div>
    @endforeach
@endsection
