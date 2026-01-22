<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Kata Sandi - Koperasi Denmart</title>
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
                Buat Kata Sandi Baru
            </h2>

            <p class="text-slate-600 dark:text-slate-400 mb-8 text-sm">
                Silakan buat kata sandi baru untuk akun Anda. Pastikan menggunakan kombinasi huruf, angka, dan karakter khusus.
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

            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300"
                        for="password">
                        <span class="text-red-500 mr-1">*</span>Kata Sandi Baru
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 pr-10 @error('password') border-red-500 @enderror"
                            id="password" name="password" placeholder="Minimal 8 karakter" type="password" required
                            autofocus />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
                            type="button" onclick="togglePassword('password', 'toggleIconPassword')">
                            <span class="material-icons text-xl" id="toggleIconPassword">visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                        Minimal 8 karakter, gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300"
                        for="password_confirmation">
                        <span class="text-red-500 mr-1">*</span>Konfirmasi Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 pr-10 @error('password_confirmation') border-red-500 @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="Ulangi kata sandi baru"
                            type="password" required />
                        <button
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
                            type="button"
                            onclick="togglePassword('password_confirmation', 'toggleIconConfirmation')">
                            <span class="material-icons text-xl" id="toggleIconConfirmation">visibility_off</span>
                        </button>
                    </div>
                    @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Strength Indicator -->
                <div class="bg-slate-50 dark:bg-slate-800 p-4 rounded">
                    <div class="text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Kekuatan Kata Sandi:</div>
                    <div class="flex gap-1 mb-2">
                        <div class="h-1.5 flex-1 bg-slate-200 dark:bg-slate-700 rounded" id="strength1"></div>
                        <div class="h-1.5 flex-1 bg-slate-200 dark:bg-slate-700 rounded" id="strength2"></div>
                        <div class="h-1.5 flex-1 bg-slate-200 dark:bg-slate-700 rounded" id="strength3"></div>
                        <div class="h-1.5 flex-1 bg-slate-200 dark:bg-slate-700 rounded" id="strength4"></div>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-400" id="strengthText">Masukkan kata sandi</div>
                </div>

                <button
                    class="w-full bg-primary hover:opacity-90 text-white font-semibold py-3 rounded transition-all shadow-lg shadow-primary/20"
                    type="submit">
                    Reset Kata Sandi
                </button>
            </form>

            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200 dark:border-slate-700"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-background-light dark:bg-slate-900 px-2 text-slate-500">atau</span>
                </div>
            </div>

            <div class="text-center">
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
        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility_off';
            }
        }

        // Password Strength Checker
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function () {
            const password = this.value;
            const strength = calculatePasswordStrength(password);

            updateStrengthIndicator(strength);
        });

        function calculatePasswordStrength(password) {
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            return Math.min(strength, 4);
        }

        function updateStrengthIndicator(strength) {
            const indicators = ['strength1', 'strength2', 'strength3', 'strength4'];
            const strengthText = document.getElementById('strengthText');
            const colors = {
                0: { bg: 'bg-slate-200 dark:bg-slate-700', text: 'Masukkan kata sandi' },
                1: { bg: 'bg-red-500', text: 'Lemah' },
                2: { bg: 'bg-orange-500', text: 'Sedang' },
                3: { bg: 'bg-yellow-500', text: 'Baik' },
                4: { bg: 'bg-green-500', text: 'Kuat' }
            };

            indicators.forEach((id, index) => {
                const element = document.getElementById(id);
                element.className = 'h-1.5 flex-1 rounded transition-all';

                if (index < strength) {
                    element.classList.add(colors[strength].bg);
                } else {
                    element.classList.add('bg-slate-200', 'dark:bg-slate-700');
                }
            });

            strengthText.textContent = colors[strength].text;
        }

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

</body>

</html>
