<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Anggota - Koperasi Denmart</title>
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

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: -1;
        }

        .step:first-child::before {
            left: 50%;
        }

        .step:last-child::before {
            right: 50%;
        }

        .step.active .step-circle {
            background: #0d6a69;
            color: white;
        }

        .step.completed .step-circle {
            background: #10b981;
            color: white;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 min-h-screen">
    <!-- Header -->
    <div class="bg-primary text-white py-6 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Registrasi Anggota</h1>
                    <p class="text-sm opacity-90 mt-1">Koperasi Denmart</p>
                </div>
                <a href="{{ route('login') }}"
                    class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded transition-colors">
                    <span class="material-icons text-sm">arrow_back</span>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Step Indicator -->
        <div class="step-indicator mb-8">
            <div class="step active" id="step-1-indicator">
                <div class="step-circle">1</div>
                <div class="text-sm font-medium">Akun</div>
            </div>
            <div class="step" id="step-2-indicator">
                <div class="step-circle">2</div>
                <div class="text-sm font-medium">Data Pribadi</div>
            </div>
            <div class="step" id="step-3-indicator">
                <div class="step-circle">3</div>
                <div class="text-sm font-medium">Data Pekerjaan</div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-6">
            <div class="flex items-center">
                <span class="material-icons text-xl mr-2">error_outline</span>
                <span>{{ session('error') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded mb-6">
            <div class="flex items-start">
                <span class="material-icons text-xl mr-2">error_outline</span>
                <div>
                    <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Registration Form -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg p-6 md:p-8">
            <form action="{{ route('register') }}" method="POST" id="registrationForm" enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Akun -->
                <div class="form-section active" id="step-1">
                    <h2 class="text-xl font-bold text-primary mb-6">Informasi Akun</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="email">
                                <span class="text-red-500 mr-1">*</span>Email
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('email') border-red-500 @enderror"
                                id="email" name="email" type="email" placeholder="contoh@email.com"
                                value="{{ old('email') }}" required />
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="password">
                                <span class="text-red-500 mr-1">*</span>Kata Sandi
                            </label>
                            <div class="relative">
                                <input
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all pr-10 @error('password') border-red-500 @enderror"
                                    id="password" name="password" type="password" placeholder="Minimal 8 karakter" required />
                                <button type="button" onclick="togglePassword('password', 'toggleIcon1')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <span class="material-icons text-xl" id="toggleIcon1">visibility_off</span>
                                </button>
                            </div>
                            @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="confirm_password">
                                <span class="text-red-500 mr-1">*</span>Ulangi Kata Sandi
                            </label>
                            <div class="relative">
                                <input
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all pr-10 @error('confirm_password') border-red-500 @enderror"
                                    id="confirm_password" name="confirm_password" type="password" placeholder="Ulangi kata sandi" required />
                                <button type="button" onclick="togglePassword('confirm_password', 'toggleIcon2')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <span class="material-icons text-xl" id="toggleIcon2">visibility_off</span>
                                </button>
                            </div>
                            @error('confirm_password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">Kata sandi harus sama dengan yang di atas</p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="button" onclick="nextStep(2)"
                            class="bg-primary hover:opacity-90 text-white font-semibold px-6 py-2.5 rounded transition-all shadow-lg shadow-primary/20">
                            Selanjutnya
                            <span class="material-icons text-sm align-middle ml-1">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Data Pribadi -->
                <div class="form-section" id="step-2">
                    <h2 class="text-xl font-bold text-primary mb-6">Data Pribadi</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="nama">
                                <span class="text-red-500 mr-1">*</span>Nama Lengkap
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('nama') border-red-500 @enderror"
                                id="nama" name="nama" type="text" placeholder="Nama lengkap sesuai KTP"
                                value="{{ old('nama') }}" required />
                            @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="jenis_kelamin">
                                    <span class="text-red-500 mr-1">*</span>Jenis Kelamin
                                </label>
                                <select
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('jenis_kelamin') border-red-500 @enderror"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih</option>
                                    <option value="Pria" {{ old('jenis_kelamin') == 'Pria' ? 'selected' : '' }}>Pria</option>
                                    <option value="Wanita" {{ old('jenis_kelamin') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                                </select>
                                @error('jenis_kelamin')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="agama">
                                    <span class="text-red-500 mr-1">*</span>Agama
                                </label>
                                <select
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('agama') border-red-500 @enderror"
                                    id="agama" name="agama" required>
                                    <option value="">Pilih</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                </select>
                                @error('agama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="tempat_lahir">
                                    <span class="text-red-500 mr-1">*</span>Tempat Lahir
                                </label>
                                <input
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('tempat_lahir') border-red-500 @enderror"
                                    id="tempat_lahir" name="tempat_lahir" type="text" placeholder="Kota/Kabupaten"
                                    value="{{ old('tempat_lahir') }}" required />
                                @error('tempat_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="tanggal_lahir">
                                    <span class="text-red-500 mr-1">*</span>Tanggal Lahir
                                </label>
                                <input
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('tanggal_lahir') border-red-500 @enderror"
                                    id="tanggal_lahir" name="tanggal_lahir" type="date"
                                    value="{{ old('tanggal_lahir') }}" required />
                                @error('tanggal_lahir')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="no_telepon">
                                <span class="text-red-500 mr-1">*</span>No. Telepon / HP
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('no_telepon') border-red-500 @enderror"
                                id="no_telepon" name="no_telepon" type="tel" placeholder="08xxxxxxxxxx"
                                value="{{ old('no_telepon') }}" required />
                            @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="alamat">
                                <span class="text-red-500 mr-1">*</span>Alamat Lengkap
                            </label>
                            <textarea
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('alamat') border-red-500 @enderror"
                                id="alamat" name="alamat" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="jenis_identitas">
                                    <span class="text-red-500 mr-1">*</span>Jenis Identitas
                                </label>
                                <select
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('jenis_identitas') border-red-500 @enderror"
                                    id="jenis_identitas" name="jenis_identitas" required>
                                    <option value="">Pilih</option>
                                    <option value="KTP" {{ old('jenis_identitas') == 'KTP' ? 'selected' : '' }}>KTP</option>
                                    <option value="SIM" {{ old('jenis_identitas') == 'SIM' ? 'selected' : '' }}>SIM</option>
                                    <option value="Paspor" {{ old('jenis_identitas') == 'Paspor' ? 'selected' : '' }}>Paspor</option>
                                </select>
                                @error('jenis_identitas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="no_identitas">
                                    <span class="text-red-500 mr-1">*</span>No. Identitas
                                </label>
                                <input
                                    class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('no_identitas') border-red-500 @enderror"
                                    id="no_identitas" name="no_identitas" type="text" placeholder="Nomor identitas"
                                    value="{{ old('no_identitas') }}" required />
                                @error('no_identitas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="nama_pasangan">
                                Nama Suami/Istri
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('nama_pasangan') border-red-500 @enderror"
                                id="nama_pasangan" name="nama_pasangan" type="text" placeholder="Kosongkan jika belum menikah"
                                value="{{ old('nama_pasangan') }}" />
                            @error('nama_pasangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="prevStep(1)"
                            class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold px-6 py-2.5 rounded transition-all">
                            <span class="material-icons text-sm align-middle mr-1">arrow_back</span>
                            Kembali
                        </button>
                        <button type="button" onclick="nextStep(3)"
                            class="bg-primary hover:opacity-90 text-white font-semibold px-6 py-2.5 rounded transition-all shadow-lg shadow-primary/20">
                            Selanjutnya
                            <span class="material-icons text-sm align-middle ml-1">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Data Pekerjaan -->
                <div class="form-section" id="step-3">
                    <h2 class="text-xl font-bold text-primary mb-6">Data Pekerjaan & Keanggotaan</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="grup_wilayah">
                                <span class="text-red-500 mr-1">*</span>Grup/Wilayah
                            </label>
                            <select
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('grup_wilayah') border-red-500 @enderror"
                                id="grup_wilayah" name="grup_wilayah" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Karyawan Koperasi" {{ old('grup_wilayah') == 'Karyawan Koperasi' ? 'selected' : '' }}>Karyawan Koperasi</option>
                                <option value="Karyawan PKWT" {{ old('grup_wilayah') == 'Karyawan PKWT' ? 'selected' : '' }}>Karyawan PKWT</option>
                                <option value="Karyawan Tetap" {{ old('grup_wilayah') == 'Karyawan Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                <option value="Non Karyawan" {{ old('grup_wilayah') == 'Non Karyawan' ? 'selected' : '' }}>Non Karyawan</option>
                                <option value="Outsourcing" {{ old('grup_wilayah') == 'Outsourcing' ? 'selected' : '' }}>Outsourcing</option>
                                <option value="Pensiun" {{ old('grup_wilayah') == 'Pensiun' ? 'selected' : '' }}>Pensiun</option>
                                <option value="Petugas Gudang Pengolah" {{ old('grup_wilayah') == 'Petugas Gudang Pengolah' ? 'selected' : '' }}>Petugas Gudang Pengolah</option>
                            </select>
                            @error('grup_wilayah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="pekerjaan">
                                <span class="text-red-500 mr-1">*</span>Pekerjaan/Jabatan
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('pekerjaan') border-red-500 @enderror"
                                id="pekerjaan" name="pekerjaan" type="text" placeholder="Contoh: Staff Admin, Manager, dll"
                                value="{{ old('pekerjaan') }}" required />
                            @error('pekerjaan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="pendapatan">
                                Pendapatan per Bulan
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500">Rp</span>
                                <input
                                    class="w-full pl-12 pr-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('pendapatan') border-red-500 @enderror"
                                    id="pendapatan" name="pendapatan" type="number" placeholder="0"
                                    value="{{ old('pendapatan') }}" />
                            </div>
                            @error('pendapatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="alamat_kantor">
                                Alamat Kantor
                            </label>
                            <textarea
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('alamat_kantor') border-red-500 @enderror"
                                id="alamat_kantor" name="alamat_kantor" rows="2" placeholder="Alamat tempat bekerja">{{ old('alamat_kantor') }}</textarea>
                            @error('alamat_kantor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="keterangan">
                                Keterangan
                            </label>
                            <textarea
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('keterangan') border-red-500 @enderror"
                                id="keterangan" name="keterangan" rows="2" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1.5 text-slate-700 dark:text-slate-300" for="foto">
                                Upload Foto Profil
                            </label>
                            <input
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all @error('foto') border-red-500 @enderror"
                                id="foto" name="foto" type="file" accept="image/*" />
                            @error('foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, JPEG. Maksimal 2MB</p>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="prevStep(2)"
                            class="bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold px-6 py-2.5 rounded transition-all">
                            <span class="material-icons text-sm align-middle mr-1">arrow_back</span>
                            Kembali
                        </button>
                        <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2.5 rounded transition-all shadow-lg shadow-green-600/20">
                            Daftar Sekarang
                            <span class="material-icons text-sm align-middle ml-1">check_circle</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary font-semibold hover:underline">Login di sini</a>
            </p>
        </div>
    </div>

    <!-- Dark Mode Toggle -->
    <button class="fixed bottom-6 right-6 p-3 rounded-full bg-slate-100 dark:bg-slate-800 shadow-lg hover:shadow-xl transition-all"
        onclick="document.documentElement.classList.toggle('dark')">
        <span class="material-icons dark:text-yellow-400 text-slate-700">dark_mode</span>
    </button>

    <script>
        // Step Navigation
        function nextStep(step) {
            // Validate current step before proceeding
            const currentStep = step - 1;
            const currentSection = document.getElementById(`step-${currentStep}`);
            const inputs = currentSection.querySelectorAll('[required]');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Harap isi semua field yang wajib diisi sebelum melanjutkan.');
                return;
            }

            // Password confirmation validation
            if (currentStep === 1) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                if (password !== confirmPassword) {
                    alert('Kata sandi tidak cocok!');
                    return;
                }

                if (password.length < 8) {
                    alert('Kata sandi minimal 8 karakter!');
                    return;
                }
            }

            // Hide current step
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });

            // Show next step
            document.getElementById(`step-${step}`).classList.add('active');

            // Update step indicators
            document.querySelectorAll('.step').forEach(stepEl => {
                stepEl.classList.remove('active', 'completed');
            });

            for (let i = 1; i <= step; i++) {
                const indicator = document.getElementById(`step-${i}-indicator`);
                if (i === step) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.add('completed');
                }
            }
        }

        function prevStep(step) {
            // Hide current step
            document.querySelectorAll('.form-section').forEach(section => {
                section.classList.remove('active');
            });

            // Show previous step
            document.getElementById(`step-${step}`).classList.add('active');

            // Update step indicators
            document.querySelectorAll('.step').forEach(stepEl => {
                stepEl.classList.remove('active', 'completed');
            });

            for (let i = 1; i <= step; i++) {
                const indicator = document.getElementById(`step-${i}-indicator`);
                if (i === step) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.add('completed');
                }
            }
        }

        // Password toggle visibility
        function togglePassword(fieldId, iconId) {
            const passwordField = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passwordField.type = 'password';
                icon.textContent = 'visibility_off';
            }
        }

        // Form submission validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Kata sandi tidak cocok!');
                return false;
            }

            // Validate all required fields
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Harap isi semua field yang wajib diisi!');
                return false;
            }
        });

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const confirmPassword = document.getElementById('confirm_password');
            if (this.value !== confirmPassword.value) {
                confirmPassword.classList.add('border-red-500');
            } else {
                confirmPassword.classList.remove('border-red-500');
            }
        });

        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password');
            if (this.value !== password.value) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-red-50, .bg-green-50');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</body>
</html>
