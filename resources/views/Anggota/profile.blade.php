@extends('layout.master')
@push('styles')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            prefix: 'tw-',
            darkMode: "class",
            important: "#tailwind-scope",
            theme: {
                extend: {
                    colors: {
                        "primary": "#197fe6",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111921",
                        "surface-light": "#ffffff",
                        "surface-dark": "#1e2936",
                        "text-primary-light": "#0e141b",
                        "text-primary-dark": "#e2e8f0",
                        "text-secondary-light": "#4e7397",
                        "text-secondary-dark": "#94a3b8",
                        "border-light": "#d0dbe7",
                        "border-dark": "#334155",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .icon-fill {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
@endpush
@section('content')
    <div id="tailwind-scope">
        <main class="tw-flex-1 tw-w-full tw-max-w-[1200px] tw-mx-auto tw-p-4 md:tw-p-8">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="tw-bg-green-50 dark:tw-bg-green-900/30 tw-border tw-border-green-200 dark:tw-border-green-800 tw-text-green-800 dark:tw-text-green-200 tw-px-4 tw-py-3 tw-rounded-lg tw-flex tw-items-center tw-gap-2 tw-mb-6">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="tw-bg-red-50 dark:tw-bg-red-900/30 tw-border tw-border-red-200 dark:tw-border-red-800 tw-text-red-800 dark:tw-text-red-200 tw-px-4 tw-py-3 tw-rounded-lg tw-flex tw-items-center tw-gap-2 tw-mb-6">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="tw-mb-8 tw-flex tw-flex-col md:tw-flex-row md:tw-items-end tw-justify-between tw-gap-4">
                <div class="tw-flex-1">
                    <h1
                        class="tw-text-text-primary-light dark:tw-text-text-primary-dark tw-text-3xl md:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-tight">
                        Profil Saya
                    </h1>
                    <p
                        class="tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-text-base md:tw-text-lg tw-mt-2">
                        Detail informasi keanggotaan koperasi simpan pinjam
                    </p>
                </div>

                <div class="tw-mt-4 md:tw-mt-0">
                    <a href="{{ route('profile.edit') }}"
                        class="tw-inline-flex tw-items-center tw-justify-center tw-gap-2 tw-bg-primary hover:tw-bg-blue-600 tw-text-white tw-px-6 tw-py-3 tw-rounded-lg tw-font-bold tw-text-sm tw-transition-all tw-shadow-lg tw-shadow-blue-500/20 active:tw-scale-95 tw-no-underline">
                        <span class="material-symbols-outlined tw-text-[20px]">edit</span>
                        Edit Profil
                    </a>
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6">
                <div class="lg:tw-col-span-4 xl:tw-col-span-3 tw-flex tw-flex-col tw-gap-6">
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-p-6 tw-flex tw-flex-col tw-items-center tw-text-center">
                        <div class="tw-relative tw-mb-4 tw-group tw-cursor-pointer">
                            <div class="tw-h-32 tw-w-32 tw-rounded-full tw-bg-cover tw-bg-center tw-border-4 tw-border-primary/20"
                                style="background-image: url('{{ $foto_url }}');">
                            </div>
                        </div>
                        <h2 class="tw-text-text-primary-light dark:tw-text-text-primary-dark tw-text-xl tw-font-bold">
                            {{ $anggota->nama }}
                        </h2>
                        <p class="tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-text-sm tw-mt-1">
                            Anggota Sejak {{ $anggota->tanggal_daftar ? $anggota->tanggal_daftar->format('Y') : '-' }}
                        </p>
                        <div class="tw-flex tw-gap-2 tw-mt-4 tw-flex-wrap tw-justify-center">
                            <span
                                class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-emerald-100 tw-text-emerald-800 dark:tw-bg-emerald-900 dark:tw-text-emerald-100">
                                <span class="tw-w-1.5 tw-h-1.5 tw-rounded-full tw-bg-emerald-500"></span>
                                {{ $status_label }}
                            </span>
                            @if($is_pengurus)
                                <span
                                    class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-primary/10 tw-text-primary dark:tw-bg-primary/20 dark:tw-text-blue-100">
                                    <span class="material-symbols-outlined tw-text-[14px]">verified_user</span>
                                    Pengurus
                                </span>
                            @endif
                        </div>
                        <div class="tw-w-full tw-h-px tw-bg-border-light dark:tw-bg-border-dark tw-my-6"></div>
                        <div class="tw-w-full tw-flex tw-flex-col tw-gap-4 tw-text-left">
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <span
                                    class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark">badge</span>
                                <div>
                                    <p
                                        class="tw-text-xs tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-font-medium">
                                        No. Anggota</p>
                                    <p
                                        class="tw-text-sm tw-font-semibold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $anggota->no_anggota ?: 'Belum ada' }}</p>
                                </div>
                            </div>
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <span
                                    class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark">confirmation_number</span>
                                <div>
                                    <p
                                        class="tw-text-xs tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-font-medium">
                                        No. Registrasi</p>
                                    <p
                                        class="tw-text-sm tw-font-semibold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $anggota->no_registrasi }}</p>
                                </div>
                            </div>
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <span
                                    class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark">groups</span>
                                <div>
                                    <p
                                        class="tw-text-xs tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-font-medium">
                                        Grup Wilayah</p>
                                    <p
                                        class="tw-text-sm tw-font-semibold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $anggota->grup_wilayah ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:tw-col-span-8 xl:tw-col-span-9 tw-flex tw-flex-col tw-gap-6">
                    {{-- Akun & Keanggotaan --}}
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 dark:tw-bg-slate-800/50">
                            <span class="material-symbols-outlined tw-text-primary">card_membership</span>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Akun &amp; Keanggotaan</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-8">
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    No. Registrasi</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->no_registrasi }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Tanggal Daftar</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $tanggal_daftar_formatted }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Petugas Pendaftar</p>
                                <div class="tw-flex tw-items-center tw-gap-2">
                                    <div
                                        class="tw-w-6 tw-h-6 tw-rounded-full tw-bg-slate-200 dark:tw-bg-slate-700 tw-flex tw-items-center tw-justify-center tw-text-xs tw-font-bold tw-text-slate-600 dark:tw-text-slate-300">
                                        {{ $anggota->user ? strtoupper(substr($anggota->user->name, 0, 2)) : 'AD' }}
                                    </div>
                                    <p
                                        class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $anggota->user ? $anggota->user->name : 'Admin' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Keterangan</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark {{ $anggota->keterangan ? '' : 'tw-italic tw-text-opacity-60' }}">
                                    {{ $anggota->keterangan ?: 'Tidak ada keterangan' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Identitas Diri --}}
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 dark:tw-bg-slate-800/50">
                            <span class="material-symbols-outlined tw-text-primary">person</span>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Identitas Diri</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-8">
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Nama Lengkap</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->nama }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Jenis Kelamin</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $jenis_kelamin_label }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Tempat, Tanggal Lahir</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->tempat_lahir ?: '-' }}, {{ $tanggal_lahir_formatted }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Agama</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->agama ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Nama Pasangan</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->nama_pasangan ?: '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Dokumen & Kontak --}}
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                        <div
                            class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden tw-flex tw-flex-col tw-h-full">
                            <div
                                class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 dark:tw-bg-slate-800/50">
                                <span class="material-symbols-outlined tw-text-primary">description</span>
                                <h3
                                    class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    Dokumen Identitas</h3>
                            </div>
                            <div class="tw-p-6 tw-flex tw-flex-col tw-gap-5 tw-flex-1">
                                <div>
                                    <p
                                        class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                        Jenis Identitas</p>
                                    <p
                                        class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $anggota->jenis_identitas ?: '-' }}</p>
                                </div>
                                <div>
                                    <p
                                        class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                        Nomor Identitas</p>
                                    <p
                                        class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark tw-font-mono tw-tracking-wide">
                                        {{ $anggota->no_identitas ?: '-' }}</p>
                                </div>
                                <div>
                                    <p
                                        class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                        Berlaku Sampai</p>
                                    <p
                                        class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                        {{ $berlaku_sampai_formatted }}</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden tw-flex tw-flex-col tw-h-full">
                            <div
                                class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 dark:tw-bg-slate-800/50">
                                <span class="material-symbols-outlined tw-text-primary">contact_phone</span>
                                <h3
                                    class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    Kontak &amp; Alamat</h3>
                            </div>
                            <div class="tw-p-6 tw-flex tw-flex-col tw-gap-5 tw-flex-1">
                                <div>
                                    <p
                                        class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                        Nomor Telepon</p>
                                    <div class="tw-flex tw-items-center tw-gap-2">
                                        <span class="material-symbols-outlined tw-text-sm tw-text-primary">call</span>
                                        <p
                                            class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                            {{ $anggota->no_telepon ?: '-' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p
                                        class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                        Alamat Rumah</p>
                                    <div class="tw-flex tw-items-start tw-gap-2">
                                        <span
                                            class="material-symbols-outlined tw-text-sm tw-text-primary tw-mt-1">home</span>
                                        <p
                                            class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark tw-leading-relaxed">
                                            {{ $anggota->alamat ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pekerjaan --}}
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-2 tw-bg-slate-50 dark:tw-bg-slate-800/50">
                            <span class="material-symbols-outlined tw-text-primary">work</span>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Pekerjaan</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-8">
                            <div>
                                <p
                                    class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Pekerjaan</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->pekerjaan ?: '-' }}</p>
                            </div>
                            <div>
                                <p
                                    class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Pendapatan Per Bulan</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $pendapatan_formatted }}</p>
                            </div>
                            <div class="md:tw-col-span-2">
                                <p
                                    class="tw-text-sm tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1">
                                    Alamat Kantor</p>
                                <p
                                    class="tw-text-base tw-font-medium tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    {{ $anggota->alamat_kantor ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
