<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — MyCam Rental</title>
    <link rel="stylesheet" href="{{ asset('template/assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/donezo.css') }}">
    <script src="{{ asset('template/assets/vendor/lucide.min.js') }}"></script>
</head>

<body
    class="bg-[#f3f5f4] text-gray-900 font-sans antialiased h-full flex flex-col selection:bg-brand-400 selection:text-white">
    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm pointer-events-none"></div>
    <div class="flex-1 flex">
        <!-- Brand panel -->
        <div class="hidden lg:flex lg:w-1/2 bg-brand-800 relative overflow-hidden p-12 flex-col justify-between">
            <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-white/5"></div>
            <div class="absolute -left-16 bottom-0 w-80 h-80 rounded-full bg-brand-700/60"></div>
            <div class="absolute right-16 bottom-24 w-40 h-40 rounded-full bg-brand-400/20"></div>

            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-white/10 text-white flex items-center justify-center">
                    <svg class="w-6 h-6 text-brand-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 5 5 0 0 1 5-5"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
                <div>
                    <p class="font-extrabold text-2xl text-white tracking-tight leading-none">MyCam</p>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-brand-300 mt-1">Rental Kamera</p>
                </div>
            </div>

            <div class="relative z-10 space-y-4">
                <h2 class="text-3xl font-extrabold text-white tracking-tight leading-tight max-w-sm">
                    Platform Rental Kamera & Lensa Terbaik
                </h2>
                <p class="text-xs text-brand-200 leading-relaxed max-w-sm">
                    Masuk ke akun Anda untuk menyewa kamera, memantau pengajuan, atau mengelola operasional studio.
                </p>
            </div>

            <p class="relative z-10 text-[10px] text-brand-300/70">&copy; {{ date('Y') }} MyCam Rental. All rights reserved.</p>
        </div>

        @yield('content')
    </div>

    <script src="{{ asset('template/assets/js/donezo.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                triggerToast('success', 'Berhasil', {!! json_encode(session('success')) !!}, 4000);
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                triggerToast('error', 'Peringatan', {!! json_encode(session('error')) !!}, 4500);
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $err)
                    triggerToast('error', 'Validasi Gagal', {!! json_encode($err) !!}, 5000);
                @endforeach
            });
        </script>
    @endif
</body>

</html>
