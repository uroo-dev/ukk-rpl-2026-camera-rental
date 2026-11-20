@extends('Layout.app')
@section('title', 'Log Aktivitas Petugas')
@section('content')
    <main class="space-y-6 pb-12">
        <div class="donezo-card overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="history" class="w-4 h-4 text-brand-700"></i>
                        <span>Log Khusus Transaksi Peminjaman (Petugas)</span>
                    </h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Arsip riwayat permohonan sewa, persetujuan syarat jaminan/pembayaran, serah terima, dan pengembalian alat.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-brand-800 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                        Total {{ $log->total() }} Aktivitas
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Petugas</th>
                            <th class="px-5 py-3">Aksi</th>
                            <th class="px-5 py-3">Keterangan Aktivitas</th>
                            <th class="px-5 py-3">Waktu Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($log as $l)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-5 py-3.5 font-bold text-gray-500">{{ $loop->iteration + ($log->currentPage() - 1) * $log->perPage() }}</td>
                                <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $l->user?->nama ?? 'Petugas' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                        {{ $l->aksi }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-700 leading-relaxed">{{ $l->keterangan }}</td>
                                <td class="px-5 py-3.5 font-mono text-[11px] text-gray-500 whitespace-nowrap">
                                    {{ $l->created_at->translatedFormat('d M Y, H:i') }}
                                    <span class="text-[10px] text-gray-400 block font-sans">({{ $l->created_at->diffForHumans() }})</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                                    Belum ada catatan aktivitas untuk akun Anda.
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
