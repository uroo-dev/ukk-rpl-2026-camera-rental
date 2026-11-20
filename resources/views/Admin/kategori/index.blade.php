@extends('Layout.app')
@section('title', 'Kategori Alat')
@section('content')
    <main class="space-y-6 pb-12">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tabel Kategori -->
            <div class="lg:col-span-2 donezo-card overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Daftar Kategori</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5">Kelompokkan alat kamera berdasarkan jenis (DSLR, Mirrorless, Lensa, Audio, Tripod, dll).</p>
                    </div>
                    <span class="text-xs font-bold text-brand-800 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                        Total: {{ $kategori->total() }} Kategori
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                            <tr>
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">Nama Kategori</th>
                                <th class="px-5 py-3">Deskripsi</th>
                                <th class="px-5 py-3 text-center">Jumlah Alat</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-gray-100">
                            @forelse ($kategori as $item)
                                <tr class="hover:bg-brand-50/30 transition">
                                    <td class="px-5 py-3.5 font-mono font-bold text-brand-800">{{ $loop->iteration + ($kategori->currentPage() - 1) * $kategori->perPage() }}</td>
                                    <td class="px-5 py-3.5 font-bold text-gray-900">{{ $item->nama_kategori }}</td>
                                    <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate">{{ $item->deskripsi ?: '-' }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $item->alat_count }} Alat
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Tooltip Edit -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-default-{{ $item->id }}')"
                                                    class="donezo-btn donezo-btn-primary p-2" title="Edit Kategori">
                                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Edit Kategori</span>
                                            </div>

                                            <!-- Tooltip Hapus -->
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-confirm-{{ $item->id }}')"
                                                    class="donezo-btn donezo-btn-danger p-2" title="Hapus Kategori">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Hapus Kategori</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                                        <i data-lucide="boxes" class="w-8 h-8 mx-auto mb-2 text-gray-300"></i>
                                        Belum ada kategori alat. Buat kategori baru di samping.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $kategori->links() }}
                </div>
            </div>

            <!-- Form Tambah Kategori -->
            <div class="space-y-4">
                <div class="donezo-card p-5 space-y-4">
                    <div class="border-b border-gray-100 pb-3">
                        <h3 class="text-sm font-bold text-gray-900">Tambah Kategori Baru</h3>
                        <p class="text-[11px] text-gray-400">Buat klasifikasi untuk mengorganisasi inventaris.</p>
                    </div>

                    <form action="{{ route('kategori.store') }}" method="POST" class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" value="{{ old('nama_kategori') }}" name="nama_kategori" class="donezo-input" placeholder="Contoh: Kamera Mirrorless" required>
                            @error('nama_kategori')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-700 block mb-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="4" class="donezo-input" placeholder="Penjelasan singkat mengenai kategori ini...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="donezo-btn donezo-btn-primary w-full mt-2">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Simpan Kategori</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Update & Confirm Delete (Di luar tabel) -->
    @foreach ($kategori as $item)
        <div id="modal-default-{{ $item->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-default-{{ $item->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-sm text-gray-900">Perbarui Kategori</h3>
                    <button type="button" onclick="closeModal('modal-default-{{ $item->id }}')" class="text-gray-400 hover:text-gray-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('kategori.update', $item->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" class="donezo-input" value="{{ $item->nama_kategori }}" name="nama_kategori" required>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Deskripsi</label>
                        <textarea name="deskripsi" class="donezo-input resize-none" rows="3" placeholder="Deskripsi kategori...">{{ $item->deskripsi }}</textarea>
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

        <div id="modal-confirm-{{ $item->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-confirm-{{ $item->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Hapus Kategori Ini?</h3>
                <p class="text-xs text-gray-500 mb-5">Apakah Anda yakin ingin menghapus kategori "<strong>{{ $item->nama_kategori }}</strong>"? Kategori yang masih berisi alat tidak dapat dihapus.</p>
                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST">
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
