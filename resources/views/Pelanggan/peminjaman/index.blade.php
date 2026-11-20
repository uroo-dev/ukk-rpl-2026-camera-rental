@extends('Pelanggan.layout.app')
@section('title', 'Riwayat Peminjaman')
@section('content')
    <main class="space-y-6 pb-12 px-5 py-6 max-w-6xl mx-auto">
        <div class="donezo-card overflow-hidden">
            <!-- Header & Filter Tabs -->
            <div class="p-5 flex flex-col lg:flex-row items-center justify-between gap-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Riwayat Peminjaman Saya</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pantau status pengajuan, masa sewa, dan bukti transaksi peminjaman.</p>
                </div>

                <div class="flex items-center gap-1.5 flex-wrap">
                    @php $currStatus = request('status', 'All'); @endphp
                    <a href="{{ route('riwayat.peminjaman') }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'All' ? 'bg-brand-800 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Semua
                    </a>
                    <a href="{{ route('riwayat.peminjaman') }}?status=Menunggu"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'Menunggu' ? 'bg-amber-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Menunggu
                    </a>
                    <a href="{{ route('riwayat.peminjaman') }}?status=Diaprove"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'Diaprove' ? 'bg-blue-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Disetujui
                    </a>
                    <a href="{{ route('riwayat.peminjaman') }}?status=Dipinjam"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'Dipinjam' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Dipinjam
                    </a>
                    <a href="{{ route('riwayat.peminjaman') }}?status=Dikembalikan"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'Dikembalikan' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Dikembalikan
                    </a>
                    <a href="{{ route('riwayat.peminjaman') }}?status=Dibatalkan"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl transition {{ $currStatus === 'Dibatalkan' ? 'bg-rose-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:text-gray-900 hover:bg-gray-200' }}">
                        Dibatalkan
                    </a>
                </div>
            </div>

            <!-- Tabel Riwayat Peminjaman -->
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/90 text-[10px] uppercase tracking-wider text-gray-500 font-extrabold border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3.5 w-36">Kode Transaksi</th>
                            <th class="px-5 py-3.5">Alat Kamera</th>
                            <th class="px-5 py-3.5 w-44">Tgl Pinjam - Kembali</th>
                            <th class="px-5 py-3.5 text-center w-24">Durasi</th>
                            <th class="px-5 py-3.5 text-right w-36">Total Biaya</th>
                            <th class="px-5 py-3.5 text-center w-28">Status</th>
                            <th class="px-5 py-3.5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($peminjaman as $item)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-brand-800">
                                    {{ $item->kode }}
                                    @if($item->nama_bukti)
                                        <p class="text-[10px] text-gray-500 font-sans font-normal mt-0.5 line-clamp-1 max-w-[180px]" title="{{ $item->nama_bukti }}">
                                            <i data-lucide="info" class="w-3 h-3 inline text-brand-600 mr-0.5"></i>{{ $item->nama_bukti }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @foreach ($item->detailPeminjaman as $detail)
                                        <div class="flex items-center gap-2.5 mb-1.5 last:mb-0">
                                            @if($detail->alat?->gambar)
                                                <img src="{{ asset('storage/' . $detail->alat->gambar) }}" class="w-8 h-8 rounded-lg object-cover border border-gray-200">
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $detail->alat?->nama_alat ?? 'Alat Kamera' }}</p>
                                                <p class="text-[10px] text-gray-400">Rp {{ number_format($detail->alat?->harga_sewa ?? 0, 0, ',', '.') }} / hari</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 text-[11px]">
                                    <div><span class="text-gray-400">Mulai:</span> {{ $item->tgl_pinjam }}</div>
                                    <div><span class="text-gray-400">Selesai:</span> {{ $item->tgl_kembali }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-gray-700">
                                    {{ $item->detailPeminjaman->first()?->lama_pinjam ?? 1 }} Hari
                                </td>
                                <td class="px-5 py-3.5 text-right font-extrabold text-gray-900">
                                    Rp {{ number_format($item->subtotal ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $badgeStyle = match($item->status) {
                                            'Menunggu' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            'Diaprove' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'Dipinjam' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                            'Dikembalikan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            default => 'bg-rose-50 text-rose-700 border-rose-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeStyle }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('invoice.peminjaman', $item->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-brand-50 text-brand-800 hover:bg-brand-100 font-bold text-xs transition border border-brand-200 shadow-2xs">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                                        <span>Invoice</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                    <i data-lucide="shopping-bag" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                                    <p class="font-semibold text-gray-600">Belum ada riwayat peminjaman.</p>
                                    <p class="text-xs text-gray-400 mt-1">Jelajahi katalog kamera dan sewa kamera impian Anda.</p>
                                    <a href="{{ route('index') }}" class="inline-block mt-4 donezo-btn donezo-btn-primary px-4 py-2 text-xs">
                                        Lihat Katalog Kamera
                                    </a>
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
@endsection
