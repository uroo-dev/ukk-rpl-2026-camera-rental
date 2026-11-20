@extends('Pelanggan.layout.app')
@section('title', 'Log Aktivitas Saya')
@section('content')

    <main class="space-y-6 pb-12 px-5 py-6 max-w-6xl mx-auto">
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-200/60 pb-4">
            <div>
                <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('index') }}" class="hover:text-brand-800 transition">Beranda</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-gray-400"></i>
                    <span class="text-brand-800 font-bold">Log Aktivitas</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">Riwayat Aktivitas Saya</h1>
                <p class="text-xs text-gray-500 mt-0.5">Catatan jejak aktivitas akun dan transaksi peminjaman Anda</p>
            </div>
            <a href="{{ route('riwayat.peminjaman') }}" class="donezo-btn donezo-btn-soft text-xs self-start sm:self-auto">
                <i data-lucide="list-ordered" class="w-3.5 h-3.5"></i>
                <span>Lihat Status Peminjaman</span>
            </a>
        </div>

        <div class="donezo-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Aksi</th>
                            <th class="px-5 py-3">Keterangan Aktivitas</th>
                            <th class="px-5 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($log as $l)
                            <tr class="hover:bg-brand-50/20 transition">
                                <td class="px-5 py-3.5 font-bold text-gray-500">{{ $loop->iteration + ($log->currentPage() - 1) * $log->perPage() }}</td>
                                <td class="px-5 py-3.5 font-bold text-brand-800">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                        {{ $l->aksi }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-700 leading-relaxed">{{ $l->keterangan }}</td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-gray-400 whitespace-nowrap">
                                    {{ $l->created_at->translatedFormat('d M Y, H:i') }}
                                    <span class="text-[10px] text-gray-400 block font-sans">({{ $l->created_at->diffForHumans() }})</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i data-lucide="activity" class="w-8 h-8 text-gray-300"></i>
                                        <p class="font-bold text-gray-600">Belum ada riwayat aktivitas</p>
                                        <p class="text-[11px] text-gray-400">Aktivitas Anda akan tercatat secara otomatis di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $log->links() }}
            </div>
        </div>
    </main>

@endsection
