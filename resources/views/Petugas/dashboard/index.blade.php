@extends('Layout.app')
@section('title', 'Petugas Dashboard - Operasional Peminjaman')
@section('content')
<main class="space-y-6 pb-12">

    <!-- Notifikasi Utama: Pengajuan yang Masih Menunggu Persetujuan -->
    @if($statusMenunggu > 0)
        <div class="p-4 sm:p-5 rounded-3xl bg-amber-500/10 border-2 border-amber-400 text-amber-950 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm animate-pulse-slow">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md">
                    <i data-lucide="bell-ring" class="w-6 h-6 animate-bounce"></i>
                </div>
                <div>
                    <h3 class="font-extrabold text-sm sm:text-base text-gray-900 flex items-center gap-2">
                        <span>Pemberitahuan Peminjaman Baru</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white shadow-xs">
                            {{ $statusMenunggu }} Menunggu
                        </span>
                    </h3>
                    <p class="text-xs text-amber-900 mt-0.5 leading-relaxed">
                        Terdapat <strong>{{ $statusMenunggu }}</strong> permohonan peminjaman alat yang belum diverifikasi. Harap segera periksa dan setujui dengan syarat jaminan/pembayaran.
                    </p>
                </div>
            </div>
            <a href="{{ route('peminjaman') }}?status=Menunggu" class="donezo-btn donezo-btn-primary text-xs shrink-0 self-stretch sm:self-auto text-center justify-center py-2.5 px-4 shadow-sm">
                <span>Proses Sekarang</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    @endif

    <!-- KPI Operasional Peminjaman Petugas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('peminjaman') }}?status=Menunggu" class="donezo-card p-5 space-y-3 hover:border-amber-400 transition group {{ $statusMenunggu > 0 ? 'border-amber-300 bg-amber-50/20' : '' }}">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                    {{ $statusMenunggu > 0 ? 'Perlu Diproses' : 'Selesai' }}
                </span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900 font-mono">{{ $statusMenunggu }}</h3>
                <p class="text-[11px] text-gray-500 font-medium">Permohonan Menunggu</p>
            </div>
        </a>

        <a href="{{ route('peminjaman') }}?status=Diaprove" class="donezo-card p-5 space-y-3 hover:border-blue-400 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="check-circle" class="w-5 h-5 text-blue-600"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-200">Siap Serah Terima</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900 font-mono">{{ $statusDiaprove }}</h3>
                <p class="text-[11px] text-gray-500 font-medium">Disetujui / Belum Diambil</p>
            </div>
        </a>

        <a href="{{ route('peminjaman') }}?status=Dipinjam" class="donezo-card p-5 space-y-3 hover:border-indigo-400 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="camera" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200">Sedang Digunakan</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900 font-mono">{{ $statusDipinjam }}</h3>
                <p class="text-[11px] text-gray-500 font-medium">Alat Sedang Dipinjam</p>
            </div>
        </a>

        <a href="{{ route('peminjaman.all') }}?status=Dikembalikan" class="donezo-card p-5 space-y-3 hover:border-emerald-400 transition group">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:scale-105 transition">
                    <i data-lucide="package-check" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Selesai</span>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-900 font-mono">{{ $statusDikembalikan }}</h3>
                <p class="text-[11px] text-gray-500 font-medium">Telah Dikembalikan</p>
            </div>
        </a>
    </div>

    <!-- Antrean Peminjaman yang Perlu Diverifikasi / Disetujui (UKK 3a) -->
    <div class="donezo-card overflow-hidden">
        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="inbox" class="w-4 h-4 text-brand-700"></i>
                    <span>Antrean Pengajuan Menunggu Persetujuan</span>
                    @if($peminjamanMenunggu->count() > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500 text-white">
                            {{ $peminjamanMenunggu->count() }} Permohonan
                        </span>
                    @endif
                </h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Petugas memverifikasi syarat identitas/pembayaran sebelum menyetujui peminjaman</p>
            </div>
            <a href="{{ route('peminjaman') }}?status=Menunggu" class="text-xs font-bold text-brand-700 hover:text-brand-900 flex items-center gap-1">
                <span>Semua Menunggu &rarr;</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                    <tr>
                        <th class="px-5 py-3">Kode Peminjaman</th>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">Rincian Alat</th>
                        <th class="px-5 py-3">Jadwal Pinjam</th>
                        <th class="px-5 py-3">Total Biaya & Ketentuan</th>
                        <th class="px-5 py-3 text-center">Tindakan Cepat</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100">
                    @forelse($peminjamanMenunggu->take(6) as $pjm)
                        <tr class="hover:bg-brand-50/20 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-brand-800">
                                {{ $pjm->kode }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-bold text-gray-900">{{ $pjm->user?->nama ?? 'Pengguna' }}</p>
                                <p class="text-[10px] text-gray-400">{{ $pjm->user?->email }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700">
                                @foreach($pjm->detailPeminjaman as $det)
                                    <div class="flex items-center gap-1.5 mb-1 last:mb-0">
                                        <span class="font-bold text-gray-800">{{ $det->alat?->nama_alat ?? 'Alat' }}</span>
                                        <span class="text-[10px] text-gray-500 bg-gray-100 px-1 py-0.5 rounded">({{ $det->lama_pinjam }} Hari)</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-5 py-3.5 text-[11px] text-gray-600">
                                <div><span class="text-gray-400">Pinjam:</span> {{ $pjm->tgl_pinjam }}</div>
                                <div><span class="text-gray-400">Kembali:</span> {{ $pjm->tgl_kembali }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-extrabold text-gray-900 font-mono">Rp {{ number_format($pjm->subtotal, 0, ',', '.') }}</p>
                                @if($pjm->nama_bukti)
                                    <p class="text-[10px] text-brand-700 mt-0.5 line-clamp-1 max-w-[180px]" title="{{ $pjm->nama_bukti }}">
                                        {{ $pjm->nama_bukti }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <div class="mycam-tooltip-container">
                                        <button type="button" onclick="openModal('modal-quick-approve-{{ $pjm->id }}')"
                                            class="donezo-btn donezo-btn-primary p-2 text-xs" title="Setujui dengan Syarat">
                                            <i data-lucide="check" class="w-4 h-4"></i>
                                            <span class="ml-1 hidden sm:inline">Setujui</span>
                                        </button>
                                        <span class="mycam-tooltip-box">Setujui</span>
                                    </div>

                                    <div class="mycam-tooltip-container">
                                        <a href="{{ route('batal', $pjm->id) }}" onclick="return confirm('Tolak dan batalkan pengajuan peminjaman {{ $pjm->kode }}?')"
                                            class="donezo-btn donezo-btn-danger p-2 text-xs" title="Tolak / Batalkan">
                                            <i data-lucide="x" class="w-4 h-4"></i>
                                        </a>
                                        <span class="mycam-tooltip-box">Tolak</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="check-circle" class="w-8 h-8 text-emerald-400"></i>
                                    <p class="text-xs font-bold text-gray-700">Semua pengajuan telah diverifikasi!</p>
                                    <p class="text-[11px] text-gray-400">Tidak ada pengajuan sewa yang tertunda saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monitoring Alat yang Sedang Dipinjam & Pengawasan Keterlambatan -->
    <div class="donezo-card overflow-hidden">
        <div class="p-5 flex items-center justify-between border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="camera" class="w-4 h-4 text-indigo-600"></i>
                    <span>Monitoring Alat Sedang Dipinjam</span>
                </h3>
                <p class="text-[11px] text-gray-400 mt-0.5">Pantau tanggal jatuh tempo pengembalian untuk mencegah keterlambatan</p>
            </div>
            <a href="{{ route('peminjaman') }}?status=Dipinjam" class="text-xs font-bold text-brand-700 hover:underline">
                Lihat Semua Dipinjam &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                    <tr>
                        <th class="px-5 py-3">Kode</th>
                        <th class="px-5 py-3">Peminjam</th>
                        <th class="px-5 py-3">Alat Dipinjam</th>
                        <th class="px-5 py-3">Batas Pengembalian</th>
                        <th class="px-5 py-3 text-center">Status Waktu</th>
                        <th class="px-5 py-3 text-center">Aksi Petugas</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-100">
                    @forelse($peminjamanSedangDipinjam as $sd)
                        @php
                            $tglKembali = \Carbon\Carbon::parse($sd->tgl_kembali)->startOfDay();
                            $hariIni = now()->startOfDay();
                            $isOverdue = $hariIni->greaterThan($tglKembali);
                            $diffDays = (int) $hariIni->diffInDays($tglKembali);
                        @endphp
                        <tr class="hover:bg-brand-50/20 transition">
                            <td class="px-5 py-3.5 font-mono font-bold text-brand-800">{{ $sd->kode }}</td>
                            <td class="px-5 py-3.5 font-semibold text-gray-900">{{ $sd->user?->nama }}</td>
                            <td class="px-5 py-3.5 text-gray-700">
                                @foreach($sd->detailPeminjaman as $d)
                                    <span class="inline-block bg-gray-100 text-gray-800 text-[11px] font-semibold px-2 py-0.5 rounded mr-1 mb-1">
                                        {{ $d->alat?->nama_alat }}
                                    </span>
                                @endforeach
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-gray-700">{{ $sd->tgl_kembali }}</td>
                            <td class="px-5 py-3.5 text-center">
                                @if($isOverdue)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-700 border border-rose-200">
                                        Telat {{ $diffDays }} Hari (Denda Rp {{ number_format($diffDays * 50000, 0, ',', '.') }})
                                    </span>
                                @elseif($diffDays === 0)
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Hari Ini Batas Terakhir
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sisa {{ $diffDays }} Hari
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <a href="{{ route('kembalikan', $sd->id) }}" onclick="return confirm('Proses pengembalian alat {{ $sd->kode }}?')"
                                    class="donezo-btn donezo-btn-primary p-1.5 text-xs inline-flex items-center gap-1" title="Proses Pengembalian">
                                    <i data-lucide="package-check" class="w-3.5 h-3.5"></i>
                                    <span>Kembalikan</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-gray-400">
                                Tidak ada alat yang sedang dipinjam saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts Row & Log Khusus Peminjaman -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Keuangan Peminjaman & Denda (UKK 3d) -->
        <div class="lg:col-span-2 donezo-card p-6 space-y-4">
            <div class="flex items-center justify-between flex-wrap gap-2">
                <div>
                    <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-4 h-4 text-brand-700"></i>
                        <span>Statistik Keuangan: Sewa vs Denda</span>
                    </h3>
                    <p class="text-[11px] text-gray-400">Pemasukan sewa dan denda keterlambatan operasional (6 Bulan Terakhir)</p>
                </div>
                <div class="flex items-center gap-3 text-xs">
                    <span class="flex items-center gap-1.5 text-brand-700 font-bold">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span> Sewa: Rp {{ number_format($totalPendapatanSewa, 0, ',', '.') }}
                    </span>
                    <span class="flex items-center gap-1.5 text-rose-600 font-bold">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Denda: Rp {{ number_format($totalPendapatanDenda, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="relative h-60">
                <canvas id="petugasFinanceChart"></canvas>
            </div>
        </div>

        <!-- Log Khusus Aktivitas Peminjaman (Hanya Peminjaman) -->
        <div class="donezo-card p-5 space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <div>
                        <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                            <i data-lucide="history" class="w-4 h-4 text-brand-700"></i>
                            <span>Log Transaksi Peminjaman</span>
                        </h3>
                        <p class="text-[11px] text-gray-400">Khusus riwayat alur pinjam & kembali</p>
                    </div>
                    <a href="{{ route('user.log') }}" class="text-[11px] font-bold text-brand-700 hover:underline">Semua &rarr;</a>
                </div>
                <div class="space-y-3 pt-3">
                    @forelse($aktivitasPeminjaman as $log)
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i data-lucide="activity" class="w-3.5 h-3.5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <p class="font-bold text-gray-800 truncate">{{ $log->aksi }}</p>
                                    <span class="text-[10px] text-gray-400 font-mono">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[11px] text-gray-500 line-clamp-1 leading-relaxed mt-0.5">{{ $log->keterangan }}</p>
                                <span class="text-[10px] text-gray-400 font-medium">Oleh: {{ $log->user?->nama ?? 'Sistem' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-6">Belum ada riwayat aktivitas peminjaman.</p>
                    @endforelse
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <a href="{{ route('user.log') }}" class="donezo-btn donezo-btn-soft w-full justify-center text-xs">
                    Buka Log Peminjaman Lengkap &rarr;
                </a>
            </div>
        </div>
    </div>

</main>

<!-- Quick Approve Modals for Petugas Dashboard (UKK Tantangan 2) -->
@foreach($peminjamanMenunggu as $pjm)
    <div id="modal-quick-approve-{{ $pjm->id }}"
        class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
        onclick="if(event.target===this)closeModal('modal-quick-approve-{{ $pjm->id }}')">
        <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="check" class="w-6 h-6 text-blue-600"></i>
            </div>
            <h3 class="font-bold text-sm text-gray-900 mb-1">Setujui Peminjaman {{ $pjm->kode }}</h3>
            <p class="text-xs text-gray-500 mb-4">
                Peminjam: <strong>{{ $pjm->user?->nama }}</strong>
            </p>

            <form action="{{ route('approve', $pjm->id) }}" method="POST" class="text-left space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Syarat Jaminan / Pembayaran <span class="text-rose-500">*</span></label>
                    <input type="text" name="syarat_jaminan" required value="{{ $pjm->nama_bukti ?: 'KTP Asli / Pembayaran Terverifikasi' }}"
                        placeholder="Contoh: KTP No. 3201... atau DP Lunas" class="donezo-input text-xs w-full">
                    <p class="text-[10px] text-gray-400 mt-1">SOP Petugas: Pastikan syarat jaminan kartu identitas / bukti bayar valid.</p>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="button" onclick="closeModal('modal-quick-approve-{{ $pjm->id }}')" class="donezo-btn donezo-btn-ghost flex-1">
                        Batal
                    </button>
                    <button type="submit" class="donezo-btn donezo-btn-primary flex-1">
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Notifikasi Toast Otomatis saat ada peminjaman status Menunggu
        @if($statusMenunggu > 0)
            setTimeout(function() {
                triggerToast('warning', 'Peminjaman Perlu Verifikasi', 'Terdapat {{ $statusMenunggu }} peminjaman yang masih menunggu persetujuan Anda.', 5500);
            }, 500);
        @endif

        // Chart Keuangan Petugas
        const ctxPetugasFinance = document.getElementById('petugasFinanceChart');
        if (ctxPetugasFinance) {
            new Chart(ctxPetugasFinance, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($keuanganLabels) !!},
                    datasets: [
                        {
                            label: 'Pendapatan Sewa (Rp)',
                            data: {!! json_encode($keuanganSewa) !!},
                            backgroundColor: '#2e7d32',
                            borderRadius: 6,
                        },
                        {
                            label: 'Denda Keterlambatan (Rp)',
                            data: {!! json_encode($keuanganDenda) !!},
                            backgroundColor: '#e11d48',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value >= 1000 ? (value / 1000) + 'k' : value);
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
