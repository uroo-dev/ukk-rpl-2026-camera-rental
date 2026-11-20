@extends('Layout.app')
@section('title', 'Kelola User')
@section('content')
    <main class="space-y-6 pb-12">

        <div class="donezo-card overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Manajemen Pengguna Staf</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5">Kelola akun staf dengan hak akses Administrator dan Petugas.</p>
                </div>
                <button type="button" onclick="openModal('modal-default')" class="donezo-btn donezo-btn-primary">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Tambah User Baru</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/80 text-[10px] uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3 text-center">Role</th>
                            <th class="px-5 py-3">Terakhir Update</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs divide-y divide-gray-100">
                        @forelse ($user as $u)
                            <tr class="hover:bg-brand-50/30 transition">
                                <td class="px-5 py-3.5 font-mono font-bold text-brand-800">{{ $loop->iteration + ($user->currentPage() - 1) * $user->perPage() }}</td>
                                <td class="px-5 py-3.5 font-bold text-gray-900">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-brand-50 text-brand-800 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($u->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $u->nama }}</p>
                                            @if($u->id === Auth::id())
                                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">Akun Anda</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-gray-600">{{ $u->email }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $u->role === 'Admin' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-[11px]">{{ $u->updated_at ? $u->updated_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tooltip Edit -->
                                        <div class="mycam-tooltip-container">
                                            <button type="button" onclick="openModal('modal-update-{{ $u->id }}')"
                                                class="donezo-btn donezo-btn-primary p-2" title="Edit User">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                            <span class="mycam-tooltip-box">Edit User</span>
                                        </div>

                                        <!-- Tooltip Hapus -->
                                        @if($u->id !== Auth::id())
                                            <div class="mycam-tooltip-container">
                                                <button type="button" onclick="openModal('modal-confirm-{{ $u->id }}')"
                                                    class="donezo-btn donezo-btn-danger p-2" title="Hapus User">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                                <span class="mycam-tooltip-box">Hapus User</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-400">
                                    Belum ada data user staf.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $user->links() }}
            </div>
        </div>
    </main>

    <!-- Modal Tambah User -->
    <div id="modal-default"
        class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
        onclick="if(event.target===this)closeModal('modal-default')">
        <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <h3 class="font-bold text-sm text-gray-900">Tambah User Staf Baru</h3>
                <button type="button" onclick="closeModal('modal-default')" class="text-gray-400 hover:text-gray-700 p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('user.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" class="donezo-input" name="nama" value="{{ old('nama') }}" required placeholder="Nama Lengkap">
                    @error('nama')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Email <span class="text-rose-500">*</span></label>
                    <input type="email" class="donezo-input" name="email" value="{{ old('email') }}" required placeholder="email@contoh.com">
                    @error('email')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="add-user-password" name="password" required class="donezo-input pr-11" placeholder="Minimal 6 karakter">
                        <button type="button" onclick="var el=document.getElementById('add-user-password'); el.type = el.type==='password' ? 'text' : 'password';"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-800 p-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Role Hak Akses <span class="text-rose-500">*</span></label>
                    <select class="donezo-input" name="role" required>
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                        <option value="Petugas" {{ old('role') === 'Petugas' ? 'selected' : '' }}>Petugas (Operasional Peminjaman)</option>
                    </select>
                    @error('role')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modal-default')" class="donezo-btn donezo-btn-ghost">Batal</button>
                    <button type="submit" class="donezo-btn donezo-btn-primary">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Simpan User</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update & Confirm Delete (Looping di luar tabel) -->
    @foreach ($user as $u)
        <!-- Modal Update User -->
        <div id="modal-update-{{ $u->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-update-{{ $u->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-md w-full p-6 transform transition-all scale-95">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                    <h3 class="font-bold text-sm text-gray-900">Perbarui Akun User</h3>
                    <button type="button" onclick="closeModal('modal-update-{{ $u->id }}')" class="text-gray-400 hover:text-gray-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form action="{{ route('user.update', $u->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" class="donezo-input" name="nama" value="{{ $u->nama }}" required>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Email <span class="text-rose-500">*</span></label>
                        <input type="email" class="donezo-input" name="email" value="{{ $u->email }}" required>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Ganti Password (Kosongkan jika tidak diganti)</label>
                        <div class="relative">
                            <input type="password" id="edit-password-{{ $u->id }}" name="password" class="donezo-input pr-11" placeholder="Minimal 6 karakter">
                            <button type="button" onclick="var el=document.getElementById('edit-password-{{ $u->id }}'); el.type = el.type==='password' ? 'text' : 'password';"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-800 p-1">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1.5 block">Role Hak Akses <span class="text-rose-500">*</span></label>
                        <select class="donezo-input" name="role" required>
                            <option value="Admin" {{ $u->role === 'Admin' ? 'selected' : '' }}>Admin (Akses Penuh)</option>
                            <option value="Petugas" {{ $u->role === 'Petugas' ? 'selected' : '' }}>Petugas (Operasional Peminjaman)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2 pt-2 border-t border-gray-100">
                        <button type="button" onclick="closeModal('modal-update-{{ $u->id }}')" class="donezo-btn donezo-btn-ghost">Batal</button>
                        <button type="submit" class="donezo-btn donezo-btn-primary">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Confirm Delete User -->
        <div id="modal-confirm-{{ $u->id }}"
            class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4"
            onclick="if(event.target===this)closeModal('modal-confirm-{{ $u->id }}')">
            <div class="bg-white rounded-3xl shadow-xl max-w-sm w-full p-6 text-center transform transition-all scale-95">
                <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-triangle" class="w-7 h-7 text-rose-600"></i>
                </div>
                <h3 class="font-bold text-sm text-gray-900 mb-1">Hapus User Ini?</h3>
                <p class="text-xs text-gray-500 mb-5">Apakah Anda yakin ingin menghapus akun staf "<strong>{{ $u->nama }}</strong>"? Tindakan ini tidak dapat dibatalkan.</p>
                <form action="{{ route('user.destroy', $u->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-2">
                        <button type="button" onclick="closeModal('modal-confirm-{{ $u->id }}')"
                            class="donezo-btn donezo-btn-ghost flex-1">Batal</button>
                        <button type="submit" class="donezo-btn donezo-btn-danger flex-1">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
