<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi Kode - Koperasi Denmart</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#0d6a69",
                        "background-light": "#ffffff",
                        "background-dark": "#0f172a",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                        body: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-pattern {
            background-image: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 40%);
        }
    </style>
</head>

<body
    class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen flex transition-colors duration-200">
    <div class="hidden lg:flex w-1/2 bg-primary relative overflow-hidden items-center justify-center p-12">
        <div class="absolute inset-0 bg-pattern"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 border-[40px] border-white/5 rounded-full"></div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/10 rounded-full">
        </div>
        <div class="relative z-10 text-white max-w-md text-center">
            <h1 class="text-6xl font-bold tracking-tight mb-2 whitespace-nowrap">Koperasi Denmart</h1>
            <p class="text-lg font-light opacity-90 leading-relaxed uppercase tracking-wider">
                Koperasi Simpan Pinjam<br />Untuk Anggota Denmart
            </p>
        </div>
    </div>

    <div
        class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 md:p-12 lg:p-24 bg-background-light dark:bg-slate-900">
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold text-primary mb-4 leading-snug">
                Verifikasi Kode
            </h2>

            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">
                Masukkan 6 digit kode yang telah dikirim ke email <span class="font-semibold text-slate-700 dark:text-slate-300">{{ session('email') ?? 'Anda' }}</span>
            </p>

            @if(session('failed'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-6"
                role="alert">
                <div class="flex items-center">
                    <span class="material-icons text-xl mr-2">error_outline</span>
                    <span>{{ session('failed') }}</span>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded mb-6"
                role="alert">
                <div class="flex items-center">
                    <span class="material-icons text-xl mr-2">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
            @endif

            <form action="{{ route('password.verify.submit') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="code">
                        <span class="text-red-500 mr-1">*</span>Kode Verifikasi
                    </label>
                    <input
                        class="w-full px-4 py-3.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 text-center text-3xl tracking-[0.5em] font-mono font-semibold @error('code') border-red-500 @enderror"
                        id="code" name="code" placeholder="000000" type="text" maxlength="6" pattern="\d{6}" required
                        autofocus oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                    @error('code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Masukkan 6 digit angka</p>
                </div>

                <button
                    class="w-full bg-primary hover:opacity-90 text-white font-semibold py-3 rounded transition-all shadow-lg shadow-primary/20"
                    type="submit">
                    Verifikasi Kode
                </button>
            </form>

            <div class="mt-8 text-center space-y-4">
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    Tidak menerima kode?
                </div>
                <form action="{{ route('password.resend') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="text-primary font-semibold hover:underline flex items-center justify-center gap-1 w-full text-sm">
                        <span class="material-icons text-sm">refresh</span>
                        Kirim Ulang Kode
                    </button>
                </form>
            </div>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-background-light dark:bg-slate-900 px-2 text-slate-500">atau</span>
                </div>
            </div>

            <div class="text-center space-y-3">
                <a class="text-slate-600 dark:text-slate-400 hover:text-primary dark:hover:text-primary text-sm flex items-center justify-center gap-1"
                    href="{{ route('password.forgot') }}">
                    <span class="material-icons text-sm">arrow_back</span>
                    Gunakan Email Lain
                </a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary text-sm flex items-center justify-center gap-1 group"
                    href="{{ route('login') }}">
                    <span class="material-icons text-sm">login</span>
                    Kembali ke Login
                </a>
            </div>
        </div>

        <button class="fixed bottom-6 right-6 p-3 rounded-full bg-slate-100 dark:bg-slate-800 shadow-md"
            onclick="document.documentElement.classList.toggle('dark')">
            <span class="material-icons dark:text-yellow-400 text-slate-700">dark_mode</span>
        </button>
    </div>

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Auto focus on code input
        document.getElementById('code').focus();
    </script>

</body>

</html>
