@extends('Layout.app')
@section('title', 'Log Aktivitas')
@section('content')
    <main class="space-y-6 pb-12">
        <div class="donezo-card overflow-hidden">
            <div class="p-5 flex flex-col sm:flex-row items-center justify-between gap-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Log Aktivitas Sistem</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Jejak audit riil seluruh tindakan dan transaksi di aplikasi MyCam.</p>
                </div>
                <span class="text-xs font-bold text-brand-800 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                    Total: {{ $log->total() }} Log
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/90 text-[10px] uppercase tracking-wider text-gray-500 font-extrabold border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-5 py-3.5 w-52">Pengguna</th>
                            <th class="px-5 py-3.5 w-48">Tindakan / Aksi</th>
                            <th class="px-5 py-3.5">Keterangan Aktivitas</th>
                            <th class="px-5 py-3.5 w-44">Waktu Eksekusi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($log as $l)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-4 py-3.5 font-mono font-bold text-center text-brand-800">{{ $loop->iteration + ($log->currentPage() - 1) * $log->perPage() }}</td>
                                <td class="px-5 py-3.5 font-bold text-gray-900">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($l->user?->nama ?? 'U', 0, 1) }}
                                        </div>
                                        <span>{{ $l->user?->nama ?? 'Pengguna Dihapus' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-brand-50 text-brand-800 border border-brand-200">
                                        {{ $l->aksi }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 max-w-md">{{ $l->keterangan }}</td>
                                <td class="px-5 py-3.5 text-gray-500 font-mono text-[11px] whitespace-nowrap">
                                    {{ $l->created_at ? $l->created_at->format('d M Y, H:i') : '-' }}
                                    <span class="text-[10px] text-gray-400 block">({{ $l->created_at ? $l->created_at->diffForHumans() : '' }})</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                                    Belum ada log aktivitas yang tercatat.
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
