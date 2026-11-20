@extends('Layout.app')
@section('title', 'Kelola Alat Kamera')
@section('content')
    <main class="space-y-6 pb-12">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tabel Daftar Alat -->
            <div class="lg:col-span-2 donezo-card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Daftar Alat & Kamera</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Kelola stok, harga sewa, dan informasi peralatan kamera.</p>
                    </div>
                    <span class="text-xs font-bold text-brand-800 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                        Total: {{ $alat->total() }} Alat
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/90 text-[10px] uppercase tracking-wider text-gray-500 font-extrabold border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-12">No</th>
                                <th class="px-5 py-3.5 text-center w-16">Foto</th>
                                <th class="px-5 py-3.5">Nama Alat</th>
                                <th class="px-5 py-3.5">Kategori</th>
                                <th class="px-5 py-3.5 text-center w-20">Stok</th>
                                <th class="px-5 py-3.5 text-right w-28">Harga/Hari</th>
                                <th class="px-5 py-3.5 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-gray-100">
                            @forelse ($alat as $item)
                                <tr class="hover:bg-brand-50/30 transition">
                                    <td class="px-4 py-3.5 font-mono font-bold text-center text-brand-800">{{ $loop->iteration + ($alat->currentPage() - 1) * $alat->perPage() }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if ($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_alat }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 shadow-xs mx-auto">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-400 mx-auto">
                                                <i data-lucide="camera" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 font-semibold text-gray-900">
                                        {{ $item->nama_alat }}
                                        @if($item->deskripsi)
                                            <p class="text-[10px] text-gray-400 truncate max-w-xs font-normal">{{ $item->deskripsi }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-gray-600">
                                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gray-100 text-gray-700">
                                            {{ $item->kategori?->nama_kategori ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($item->stok > 0)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $item->stok }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">
                                                Habis (0)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right font-bold text-gray-900">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tooltip Edit -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-default-{{ $item->id }}')"
                                                    class="donezo-btn donezo-btn-primary p-2" title="Edit Alat">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Edit Alat</span>
                                            </div>

                                            <!-- Tooltip Hapus -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-confirm-{{ $item->id }}')"
                                                    class="donezo-btn donezo-btn-danger p-2" title="Hapus Alat">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Hapus Alat</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-gray-400">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Belum ada data alat kamera. Tambahkan melalui form di samping.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $alat->links() }}
                </div>
            </div>

            <!-- Form Tambah Alat -->
            <div class="space-y-4">
                <div class="donezo-card p-5 space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-bold text-gray-900">Tambah Alat Baru</h3>
                        <p class="text-[11px] text-gray-400">Masukkan detail alat kamera untuk disewakan.</p>
                    </div>

                    <form action="{{ route('alat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Nama Alat <span class="text-rose-500">*</span></label>
                            <input type="text" value="{{ old('nama_alat') }}" name="nama_alat" class="donezo-input" placeholder="Contoh: Sony A7 IV" required>
                            @error('nama_alat')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Harga Sewa per Hari (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="harga_sewa" value="{{ old('harga_sewa') }}" class="donezo-input" placeholder="Contoh: 150000" min="0" required>
                            @error('harga_sewa')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] font-bold text-gray-700 block mb-1">Stok Awal <span class="text-rose-500">*</span></label>
                                <input type="number" name="stok" value="{{ old('stok', 1) }}" min="0" required class="donezo-input">
                                @error('stok')
                                    <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-[11px] font-bold text-gray-700 block mb-1">Kategori <span class="text-rose-500">*</span></label>
                                <select name="kategori_id" class="donezo-input" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Deskripsi & Kelengkapan</label>
                            <textarea name="deskripsi" rows="3" class="donezo-input" placeholder="Lensa kit, baterai 2x, charger, strap...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Foto Alat (Maks. 2MB)</label>
                            <input type="file" name="gambar" accept="image/*" class="donezo-input text-xs">
                            @error('gambar')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="donezo-btn donezo-btn-primary w-full mt-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Simpan Alat</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Update & Confirm Delete (Diletakkan di luar tabel agar valid HTML) -->
    @foreach ($alat as $item)
        <!-- Modal Update Alat -->
        <div id="modal-default-{{ $item->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-default-{{ $item->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95 overflow-y-auto max-h-[90vh]">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-sm text-gray-900">Perbarui Data Alat</h3>
                    <button type="button" onclick="closeModal('modal-default-{{ $item->id }}')" class="text-gray-400 hover:text-gray-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('alat.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Nama Alat <span class="text-rose-500">*</span></label>
                        <input type="text" value="{{ $item->nama_alat }}" name="nama_alat" class="donezo-input" required>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Harga Sewa per Hari (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga_sewa" value="{{ $item->harga_sewa }}" class="donezo-input" min="0" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Stok Saat Ini <span class="text-rose-500">*</span></label>
                            <input type="number" name="stok" value="{{ $item->stok }}" min="0" required class="donezo-input">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Kategori <span class="text-rose-500">*</span></label>
                            <select class="donezo-input" name="kategori_id" required>
                                <option disabled>Pilih Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}" {{ $item->kategori_id == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="donezo-input">{{ $item->deskripsi }}</textarea>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Ganti Foto Alat (Opsional)</label>
                        @if($item->gambar)
                            <div class="flex items-center gap-2 mb-2">
                                <img src="{{ asset('storage/' . $item->gambar) }}" class="w-12 h-12 rounded-xl object-cover border border-gray-200">
                                <span class="text-[11px] text-gray-500">Foto saat ini</span>
                            </div>
                        @endif
                        <input type="file" name="gambar" accept="image/*" class="donezo-input text-xs">
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-default-{{ $item->id }}')" class="donezo-btn donezo-btn-ghost">
                            Batal
                        </button>
                        <button type="submit" class="donezo-btn donezo-btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Confirm Delete Alat -->
        <div id="modal-confirm-{{ $item->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-confirm-{{ $item->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Hapus Alat Ini?</h3>
                <p class="text-xs text-gray-500 mb-5">Apakah Anda yakin ingin menghapus alat "<strong>{{ $item->nama_alat }}</strong>"? Tindakan ini tidak dapat dibatalkan.</p>
                <form action="{{ route('alat.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-2">
                        <button type="button" onclick="closeModal('modal-confirm-{{ $item->id }}')"
                            class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                        <button type="submit" class="donezo-btn donezo-btn-danger flex-1">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
