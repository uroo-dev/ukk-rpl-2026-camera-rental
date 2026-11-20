@extends('Pelanggan.layout.app')
@section('title', $alat->nama_alat)
@section('content')

    <main class="space-y-6 pb-12 px-5 py-6 max-w-6xl mx-auto">
        <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('index') }}" class="hover:text-brand-800 transition">Beranda</a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
            <span class="text-gray-400">{{ $alat->kategori?->nama_kategori }}</span>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
            <span class="text-brand-800 font-bold truncate">{{ $alat->nama_alat }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Galeri Foto & Deskripsi -->
            <div class="lg:col-span-2 space-y-5">
                <div class="donezo-card p-8 flex items-center justify-center bg-white min-h-[360px] relative overflow-hidden">
                    @if($alat->gambar)
                        <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}"
                            class="max-h-80 w-auto object-contain rounded-2xl shadow-sm transition hover:scale-105 duration-300">
                    @else
                        <div class="w-24 h-24 rounded-3xl bg-gray-50 text-gray-300 flex items-center justify-center">
                            <i data-lucide="camera" class="w-12 h-12"></i>
                        </div>
                    @endif
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold {{ $alat->stok > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $alat->stok > 0 ? 'Tersedia (' . $alat->stok . ' Unit)' : 'Stok Habis' }}
                    </span>
                </div>

                <!-- Deskripsi & Spesifikasi -->
                <div class="donezo-card p-6 space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-bold text-gray-900">Deskripsi & Kelengkapan Alat</h3>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-line">{{ $alat->deskripsi ?: 'Tidak ada deskripsi tambahan untuk alat ini.' }}</p>
                </div>
            </div>

            <!-- Panel Sewa & Transaksi -->
            <div class="space-y-4">
                <div class="donezo-card p-6 space-y-5">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md">
                            {{ $alat->kategori?->nama_kategori ?? 'Kamera' }}
                        </span>
                        <h2 class="font-extrabold text-2xl text-gray-900 tracking-tight mt-1.5">{{ $alat->nama_alat }}</h2>
                    </div>

                    <div class="flex items-baseline gap-2 pt-1 border-t border-gray-100">
                        <span class="text-xs text-gray-500 font-semibold">Harga Sewa:</span>
                        <p class="text-2xl font-extrabold text-brand-800 tracking-tight">
                            Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}
                        </p>
                        <span class="text-[11px] text-gray-400">/ hari</span>
                    </div>

                    <!-- Tombol Aksi Sewa -->
                    <div class="pt-2">
                        @guest
                            <a href="{{ route('login') }}" class="donezo-btn donezo-btn-primary w-full justify-center py-3 text-xs">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                <span>Login untuk Meminjam</span>
                            </a>
                        @else
                            @if(Auth::user()->role !== 'Pelanggan')
                                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                                    <p class="font-bold">Akses Staf ({{ Auth::user()->role }})</p>
                                    <p class="text-[11px] mt-0.5">Anda sedang login sebagai staf. Fitur pengajuan sewa ini ditujukan untuk akun Pelanggan.</p>
                                </div>
                            @elseif($cekPeminjaman)
                                <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs space-y-2">
                                    <p class="font-bold flex items-center gap-1.5">
                                        <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600"></i>
                                        Peminjaman Aktif Terdeteksi
                                    </p>
                                    <p class="text-[11px]">Anda memiliki peminjaman yang sedang diproses atau belum dikembalikan. Selesaikan transaksi tersebut sebelum meminjam alat lain.</p>
                                    <a href="{{ route('riwayat.peminjaman') }}" class="text-brand-800 font-bold underline block text-[11px]">
                                        Lihat Riwayat Peminjaman &rarr;
                                    </a>
                                </div>
                            @elseif($alat->stok <= 0)
                                <button type="button" disabled class="donezo-btn donezo-btn-ghost w-full justify-center opacity-60 cursor-not-allowed text-xs">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    <span>Stok Sedang Habis</span>
                                </button>
                            @else
                                <div class="space-y-2">
                                    <button type="button" onclick="addToCartAndOpen({{ json_encode([
                                        'id' => $alat->id,
                                        'nama_alat' => $alat->nama_alat,
                                        'harga_sewa' => (float) $alat->harga_sewa,
                                        'kategori' => $alat->kategori?->nama_kategori ?? 'Kamera',
                                        'gambar' => $alat->gambar ? asset('storage/' . $alat->gambar) : null,
                                        'stok' => $alat->stok
                                    ]) }})" class="donezo-btn donezo-btn-primary w-full justify-center py-3 text-xs">
                                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                        <span>Sewa Sekarang</span>
                                    </button>
                                    <button type="button" onclick="addToCart({{ json_encode([
                                        'id' => $alat->id,
                                        'nama_alat' => $alat->nama_alat,
                                        'harga_sewa' => (float) $alat->harga_sewa,
                                        'kategori' => $alat->kategori?->nama_kategori ?? 'Kamera',
                                        'gambar' => $alat->gambar ? asset('storage/' . $alat->gambar) : null,
                                        'stok' => $alat->stok
                                    ]) }})" class="donezo-btn donezo-btn-soft w-full justify-center py-2.5 text-xs">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        <span>+ Masukkan Keranjang</span>
                                    </button>
                                </div>
                            @endif
                        @endguest
                    </div>
                </div>

                <!-- Info Card -->
                <div class="donezo-card p-5 space-y-3.5 text-xs text-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                            <i data-lucide="check-shield" class="w-4 h-4 text-brand-800"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Jaminan Kualitas</p>
                            <p class="text-[11px] text-gray-400">Unit terawat, sensor bersih, dan siap pakai.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                            <i data-lucide="clock" class="w-4 h-4 text-brand-800"></i>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">Pengambilan Fleksibel</p>
                            <p class="text-[11px] text-gray-400">Ambil di gerai MyCam setelah disetujui petugas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Drawer Modal Ajukan Peminjaman -->
    @auth
        @if(Auth::user()->role === 'Pelanggan' && !$cekPeminjaman && $alat->stok > 0)
            <div id="modal-drawer-pinjam"
                class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex justify-end"
                onclick="if(event.target===this)closeModal('modal-drawer-pinjam')">
                <div class="w-full max-w-md bg-white shadow-2xl p-6 overflow-y-auto h-full flex flex-col justify-between transform transition-all animate-slide-left">
                    <div>
                        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                            <div>
                                <h3 class="font-bold text-base text-gray-900">Form Pengajuan Peminjaman</h3>
                                <p class="text-[11px] text-gray-400">Lengkapi jadwal sewa alat kamera Anda.</p>
                            </div>
                            <button type="button" onclick="closeModal('modal-drawer-pinjam')" class="p-1 text-gray-400 hover:text-gray-700">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>

                        <form id="form-ajukan-peminjaman" action="{{ route('ajukan.peminjaman', $alat->id) }}" method="POST" class="space-y-5 mt-5">
                            @csrf

                            <!-- Ringkasan Alat -->
                            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 flex items-center gap-3">
                                @if($alat->gambar)
                                    <img src="{{ asset('storage/' . $alat->gambar) }}" class="w-14 h-14 rounded-xl object-cover border border-gray-200">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i data-lucide="camera" class="w-6 h-6"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-sm text-gray-900">{{ $alat->nama_alat }}</p>
                                    <p class="text-xs font-extrabold text-brand-800">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }} <span class="text-[10px] text-gray-400 font-normal">/ hari</span></p>
                                    <p class="text-[10px] text-emerald-600 font-semibold mt-0.5">Stok tersedia: {{ $alat->stok }} unit</p>
                                </div>
                            </div>

                            <!-- Input Tanggal Pinjam & Kembali -->
                            <div class="space-y-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Tanggal Mulai Pinjam <span class="text-rose-500">*</span></label>
                                    <input type="date" id="input_tgl_pinjam" name="tgl_pinjam" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" required class="donezo-input text-xs">
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Tanggal Rencana Kembali <span class="text-rose-500">*</span></label>
                                    <input type="date" id="input_tgl_kembali" name="tgl_kembali" min="{{ now()->toDateString() }}" value="{{ now()->addDay()->toDateString() }}" required class="donezo-input text-xs">
                                </div>
                            </div>

                            <!-- Estimasi Biaya -->
                            <div class="p-4 rounded-2xl bg-brand-50/60 border border-brand-200 space-y-2 text-xs">
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>Durasi Sewa:</span>
                                    <span id="label_durasi" class="font-bold text-gray-900">1 Hari</span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600">
                                    <span>Tarif Harian:</span>
                                    <span>Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center justify-between pt-2 border-t border-brand-200 text-sm font-extrabold text-brand-900">
                                    <span>Perkiraan Total:</span>
                                    <span id="label_total">Rp {{ number_format($alat->harga_sewa, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <p class="text-[10px] text-gray-400 leading-relaxed">
                                * Peminjaman minimal 1 hari. Saat pengambilan alat di gerai, Anda diwajibkan menyerahkan kartu identitas (KTP/SIM asli) sebagai jaminan.
                            </p>
                        </form>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-drawer-pinjam')" class="donezo-btn donezo-btn-ghost flex-1">
                            Batal
                        </button>
                        <button type="submit" form="form-ajukan-peminjaman" class="donezo-btn donezo-btn-primary flex-1">
                            Kirim Pengajuan
                        </button>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const tglPinjam = document.getElementById('input_tgl_pinjam');
                    const tglKembali = document.getElementById('input_tgl_kembali');
                    const labelDurasi = document.getElementById('label_durasi');
                    const labelTotal = document.getElementById('label_total');
                    const hargaPerHari = {{ $alat->harga_sewa }};

                    function hitungEstimasi() {
                        if (!tglPinjam || !tglKembali) return;
                        const start = new Date(tglPinjam.value);
                        const end = new Date(tglKembali.value);

                        if (isNaN(start.getTime()) || isNaN(end.getTime())) return;

                        // Pastikan end >= start
                        if (end < start) {
                            tglKembali.value = tglPinjam.value;
                        }

                        const diffTime = Math.abs(end - start);
                        let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                        if (diffDays === 0) diffDays = 1;

                        const total = diffDays * hargaPerHari;

                        if (labelDurasi) labelDurasi.textContent = diffDays + ' Hari';
                        if (labelTotal) labelTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
                    }

                    if (tglPinjam) tglPinjam.addEventListener('change', function() {
                        if (tglKembali) tglKembali.min = tglPinjam.value;
                        hitungEstimasi();
                    });

                    if (tglKembali) tglKembali.addEventListener('change', hitungEstimasi);
                    hitungEstimasi();
                });
            </script>
        @endif
    @endauth

@endsection
