@extends('Pelanggan.layout.app')
@section('title', 'Katalog Sewa Kamera & Lensa')
@section('content')

    <!-- Hero Banner -->
    <section class="max-w-6xl mx-auto px-5 pt-12 pb-8 text-center">
        <div class="max-w-2xl mx-auto space-y-3">
            <span class="px-3 py-1 text-xs font-bold rounded-full bg-brand-50 text-brand-800 border border-brand-200 inline-block">
                Rental Kamera Terlengkap & Terpercaya
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                Tangkap Momen Terbaik dengan Lensa Profesional
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 leading-relaxed max-w-xl mx-auto">
                Sewa kamera DSLR, Mirrorless, Lensa Cinema, lighting, dan perlengkapan audio dengan proses mudah, cepat, dan harga transparan di MyCam.
            </p>
        </div>
    </section>

    <!-- UKK Tantangan 1: Notifikasi Tanggungan Peminjaman Aktif -->
    @if($cekPeminjaman)
        <div class="max-w-6xl mx-auto px-5 mb-6">
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-amber-700"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-amber-900">Perhatian: Anda Memiliki Tanggungan Peminjaman Aktif</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">Sesuai SOP rental, Anda belum dapat mengajukan peminjaman alat baru sampai peminjaman aktif sebelumnya diselesaikan.</p>
                    </div>
                </div>
                <a href="{{ route('riwayat.peminjaman') }}" class="donezo-btn donezo-btn-primary text-xs shrink-0 self-stretch sm:self-auto text-center justify-center">
                    <span>Lihat Status Transaksi</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    @endif

    <!-- Katalog Produk -->
    <section class="max-w-6xl mx-auto px-5 pb-16 space-y-6">
        <div class="flex items-center justify-between border-b border-gray-200/60 pb-4">
            <div>
                <h2 class="text-base font-bold text-gray-900">Katalog Peralatan Tersedia</h2>
                <p class="text-xs text-gray-400 mt-0.5">Pilih kamera atau perlengkapan yang Anda butuhkan</p>
            </div>
            <span class="text-xs font-bold text-gray-500">
                Menampilkan {{ $alat->count() }} dari {{ $alat->total() }} alat
            </span>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($alat as $a)
                <div class="donezo-card overflow-hidden group flex flex-col justify-between hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div>
                        <div class="relative h-48 overflow-hidden bg-gray-50 border-b border-gray-100 flex items-center justify-center">
                            @if($a->gambar)
                                <img src="{{ asset('storage/' . $a->gambar) }}" alt="{{ $a->nama_alat }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="text-gray-300 flex items-center justify-center">
                                    <i data-lucide="camera" class="w-12 h-12"></i>
                                </div>
                            @endif

                            <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $a->stok > 0 ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                                {{ $a->stok > 0 ? 'Tersedia' : 'Habis' }}
                            </span>

                            <span class="absolute bottom-3 right-3 text-[10px] font-bold bg-black/60 backdrop-blur-xs text-white px-2 py-0.5 rounded-md">
                                {{ $a->kategori?->nama_kategori ?? 'Alat' }}
                            </span>
                        </div>

                        <div class="p-4 space-y-2">
                            <h3 class="font-bold text-sm text-gray-900 truncate" title="{{ $a->nama_alat }}">{{ $a->nama_alat }}</h3>
                            <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed">
                                {{ $a->deskripsi ?: 'Kondisi prima, siap pakai untuk keperluan fotografi & videografi.' }}
                            </p>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                <div>
                                    <p class="text-[10px] text-gray-400 font-semibold">Harga Sewa</p>
                                    <p class="font-extrabold text-brand-800 text-base">
                                        Rp {{ number_format($a->harga_sewa, 0, ',', '.') }}
                                        <span class="text-[10px] text-gray-400 font-normal">/hari</span>
                                    </p>
                                </div>
                                <span class="text-[11px] font-bold {{ $a->stok > 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-1 rounded-lg">
                                    Stok: {{ $a->stok }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 pt-0 grid grid-cols-2 gap-2">
                        <a href="{{ route('show', $a->id) }}" class="donezo-btn donezo-btn-ghost justify-center text-xs py-2">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span>Detail</span>
                        </a>
                        @if($a->stok > 0)
                            <button type="button" onclick="addToCart({{ json_encode([
                                'id' => $a->id,
                                'nama_alat' => $a->nama_alat,
                                'harga_sewa' => (float) $a->harga_sewa,
                                'kategori' => $a->kategori?->nama_kategori ?? 'Kamera',
                                'gambar' => $a->gambar ? asset('storage/' . $a->gambar) : null,
                                'stok' => $a->stok
                            ]) }})" class="donezo-btn donezo-btn-primary justify-center text-xs py-2" title="Tambah ke Keranjang Sewa Multi-Alat">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                <span>+ Sewa</span>
                            </button>
                        @else
                            <button type="button" disabled class="donezo-btn donezo-btn-ghost opacity-50 justify-center text-xs py-2 cursor-not-allowed">
                                <span>Habis</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-gray-400">
                    <i data-lucide="camera-off" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                    <p class="font-semibold text-gray-700">Belum ada alat kamera yang terdaftar.</p>
                    <p class="text-xs text-gray-400 mt-1">Silakan kunjungi kembali beberapa saat lagi.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $alat->links() }}
        </div>
    </section>
@endsection
