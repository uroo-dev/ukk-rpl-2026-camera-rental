@extends('Auth.layout')
@section('title', 'Daftar Akun Baru')
@section('content')
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-md space-y-6">
            <div class="lg:hidden flex items-center gap-2.5 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-brand-800 text-white flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <p class="font-extrabold text-xl text-gray-900 tracking-tight">MyCam</p>
            </div>

            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Akun Pelanggan</h1>
                <p class="text-xs text-gray-500 mt-1">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-brand-800 hover:underline">Masuk di sini</a>
                </p>
            </div>

            <form action="{{ route('register.proses') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required class="donezo-input" placeholder="Nama Lengkap">
                        @error('nama')
                            <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Email Aktif <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="donezo-input" placeholder="email@gmail.com">
                        @error('email')
                            <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-gray-700 block mb-1">Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="reg-password" name="password" required class="donezo-input pr-11" placeholder="Minimal 6 karakter">
                        <button type="button" onclick="var i=document.getElementById('reg-password'); i.type = i.type === 'password' ? 'text' : 'password';"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-800 p-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-700 mb-1 block">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select class="donezo-input text-xs" name="jenis_kelamin" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-[11px] font-bold text-gray-700 block mb-1">Nomor WhatsApp / HP <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="donezo-input" placeholder="08xxxxxxxxxx">
                        @error('no_hp')
                            <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-gray-700 block mb-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="alamat" class="donezo-input" rows="3" placeholder="Alamat tempat tinggal / domisili sesuai KTP..." required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="donezo-btn donezo-btn-primary w-full justify-center py-2.5 text-xs mt-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Daftar Akun Baru</span>
                </button>
            </form>

            <div class="text-center pt-2">
                <a href="{{ route('index') }}" class="text-xs text-gray-400 hover:text-gray-600">
                    &larr; Kembali ke Katalog Depan
                </a>
            </div>
        </div>
    </div>
@endsection
