<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — MyCam</title>
    <link rel="stylesheet" href="{{ asset('template/assets/css/tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/donezo.css') }}">
    <script src="{{ asset('template/assets/vendor/lucide.min.js') }}"></script>
    <script src="{{ asset('template/assets/vendor/chart.min.js') }}"></script>
    <style>
        /* Custom Tooltip Styling */
        .mycam-tooltip-container {
            position: relative;
            display: inline-flex;
        }
        .mycam-tooltip-box {
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: #111827;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.15s ease-in-out;
            z-index: 50;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .mycam-tooltip-box::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 4px;
            border-style: solid;
            border-color: #111827 transparent transparent transparent;
        }
        .mycam-tooltip-container:hover .mycam-tooltip-box {
            opacity: 1;
            visibility: visible;
        }
    </style>
</head>

<body
    class="bg-[#f3f5f4] text-gray-900 font-sans antialiased h-full flex flex-col selection:bg-brand-400 selection:text-white">

    <div id="toast-container" class="fixed top-5 right-5 z-[9999] flex flex-col gap-2.5 max-w-sm pointer-events-none"></div>

    <div class="flex h-screen overflow-hidden p-2 sm:p-4 gap-4">
        @include('Layout.sidebar')
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/30 backdrop-blur-xs z-30 hidden md:hidden"></div>

        <div class="flex-1 flex flex-col h-full overflow-y-auto pr-1">
            @include('Layout.header')

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('template/assets/js/donezo.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
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
                triggerToast('error', 'Terjadi Kesalahan', {!! json_encode(session('error')) !!}, 4500);
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

    @stack('scripts')
</body>

</html>
