@extends('Auth.layout')
@section('title', 'Masuk Akun')
@section('content')
    <!-- Form panel -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-12">
        <div class="w-full max-w-sm space-y-6">
            <div class="lg:hidden flex items-center gap-2.5 mb-4">
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
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-gray-500 mt-1">Masuk dengan nama pengguna Anda untuk melanjutkan.</p>
            </div>

            <form action="{{ route('login.proses') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[11px] font-bold text-gray-700 block mb-1.5">Nama Pengguna <span class="text-rose-500">*</span></label>
                    <input type="text" value="{{ old('nama') }}" name="nama" required class="donezo-input" placeholder="Masukkan nama Anda">
                    @error('nama')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-[11px] font-bold text-gray-700 block mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" id="login-password" name="password" required class="donezo-input pr-11" placeholder="••••••••">
                        <button type="button" onclick="var i=document.getElementById('login-password'); i.type = i.type === 'password' ? 'text' : 'password';"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-800 p-1">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-[10px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="donezo-btn donezo-btn-primary w-full justify-center py-2.5 text-xs mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Masuk ke Akun</span>
                </button>
            </form>

            <div class="pt-4 border-t border-gray-100 text-center">
                <p class="text-[11px] text-gray-500">
                    Belum memiliki akun pelanggan?
                    <a href="{{ route('register') }}" class="font-bold text-brand-800 hover:underline">
                        Daftar akun sekarang
                    </a>
                </p>
                <a href="{{ route('index') }}" class="inline-block mt-3 text-xs text-gray-400 hover:text-gray-600">
                    &larr; Kembali ke Katalog Depan
                </a>
            </div>
        </div>
    </div>
@endsection
