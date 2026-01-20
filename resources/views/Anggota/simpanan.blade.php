@extends('layout.master')

@push('styles')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#2bee75",
                        "background-light": "#f6f8f7",
                        "background-dark": "#102217",
                        "surface-light": "#ffffff",
                        "surface-dark": "#18281e",
                        "border-light": "#dbe6df",
                        "border-dark": "#2a3c30",
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
    <div class="tw-relative tw-flex tw-h-full tw-w-full tw-flex-col group/design-root tw-overflow-x-hidden">
        <div class="tw-layout-container tw-flex tw-h-full tw-grow tw-flex-col">
            <div class="tw-mx-auto tw-w-full tw-max-w-7xl tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-6 md:tw-py-8">
                <div class="tw-flex tw-flex-col tw-gap-6 md:tw-gap-8">

                    {{-- Alert Messages --}}
                    @if(session('success'))
                        <div class="tw-bg-green-50 dark:tw-bg-green-900/30 tw-border tw-border-green-200 dark:tw-border-green-800 tw-text-green-800 dark:tw-text-green-200 tw-px-4 tw-py-3 tw-rounded-lg tw-flex tw-items-center tw-gap-2">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="tw-bg-red-50 dark:tw-bg-red-900/30 tw-border tw-border-red-200 dark:tw-border-red-800 tw-text-red-800 dark:tw-text-red-200 tw-px-4 tw-py-3 tw-rounded-lg tw-flex tw-items-center tw-gap-2">
                            <span class="material-symbols-outlined">error</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    {{-- Header --}}
                    <div class="tw-flex tw-flex-wrap tw-justify-between tw-items-end tw-gap-4">
                        <div class="tw-flex tw-flex-col tw-gap-2">
                            <h1
                                class="tw-text-slate-900 dark:tw-text-white tw-text-2xl md:tw-text-3xl lg:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-[-0.033em]">
                                Dashboard Simpanan Anda
                            </h1>
                            <div class="tw-flex tw-items-center tw-gap-2 tw-text-slate-500 dark:tw-text-slate-400">
                                <span class="material-symbols-outlined tw-text-lg">account_balance</span>
                                <p class="tw-text-sm md:tw-text-base tw-font-medium tw-leading-normal">Koperasi Daun Emas Nusantara</p>
                            </div>
                        </div>
                        <div class="tw-text-right tw-hidden md:tw-block">
                            <p class="tw-text-sm tw-font-medium tw-text-slate-500 dark:tw-text-slate-400">Selamat Datang, Anggota</p>
                            <p class="tw-text-sm tw-font-bold tw-text-slate-900 dark:tw-text-white">{{ $anggota->nama }}</p>
                        </div>
                    </div>

                    {{-- Stats Cards --}}
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
                        {{-- Total Simpanan --}}
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div
                                    class="tw-p-2 tw-bg-green-100 dark:tw-bg-green-900/30 tw-rounded-full tw-text-green-700 dark:tw-text-green-400">
                                    <span class="material-symbols-outlined tw-text-xl">account_balance_wallet</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Total Simpanan</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                Rp {{ number_format($totalSimpanan, 0, ',', '.') }}</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                Dari total plafon Rp {{ number_format($plafonTotal, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Simpanan Aktif --}}
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div
                                    class="tw-p-2 tw-bg-blue-100 dark:tw-bg-blue-900/30 tw-rounded-full tw-text-blue-700 dark:tw-text-blue-400">
                                    <span class="material-symbols-outlined tw-text-xl">assignment</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Simpanan Aktif</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                {{ $simpananList->count() }} Rekening</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                {{ implode(', ', $simpananAktifDetail) ?: 'Belum ada simpanan' }}
                            </p>
                        </div>

                        {{-- Status Kolektibilitas --}}
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div class="tw-p-2 tw-bg-{{ $statusKolektibilitas['color'] }}-100 dark:tw-bg-{{ $statusKolektibilitas['color'] }}-900/30 tw-rounded-full tw-text-{{ $statusKolektibilitas['color'] }}-700 dark:tw-text-{{ $statusKolektibilitas['color'] }}-400">
                                    <span class="material-symbols-outlined tw-text-xl">verified</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Status Kolektibilitas</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                {{ $statusKolektibilitas['status'] }}</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                {{ $statusKolektibilitas['keterangan'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    <div class="tw-flex tw-flex-row tw-flex-wrap tw-justify-between tw-items-center tw-gap-4">
                        <a href="{{ route('simpanan.penarikan') }}"
                            class="tw-group tw-flex tw-flex-1 tw-min-w-[180px] tw-cursor-pointer tw-items-center tw-justify-center tw-rounded-lg tw-h-12 tw-px-4 tw-bg-primary hover:tw-bg-green-400 tw-text-slate-900 tw-gap-2 tw-text-sm md:tw-text-base tw-font-bold tw-transition-all tw-shadow-md hover:tw-shadow-lg tw-shadow-primary/20 tw-no-underline">
                            <span class="material-symbols-outlined group-hover:tw-scale-110 tw-transition-transform">
                                add_circle
                            </span>
                            Ajukan Pengambilan Simpanan
                        </a>
                    </div>

                    {{-- Riwayat Transaksi --}}
                    <div class="@container tw-w-full">
                        <h2
                            class="tw-text-xl tw-font-bold tw-text-slate-900 dark:tw-text-slate-50 tw-mt-10 tw-mb-3">
                            Riwayat Transaksi
                        </h2>

                        <div
                            class="tw-bg-white dark:tw-bg-slate-800/50 tw-rounded-xl tw-shadow-sm tw-border tw-border-slate-200 dark:tw-border-slate-700 tw-overflow-hidden">

                            @if($transaksiList->count() > 0)
                                <div class="tw-overflow-x-auto">
                                    <table class="tw-w-full tw-text-sm tw-text-left">
                                        <thead
                                            class="tw-bg-slate-100 dark:tw-bg-slate-800 tw-text-slate-700 dark:tw-text-slate-300 tw-uppercase">
                                            <tr>
                                                <th class="tw-px-6 tw-py-3">Tanggal</th>
                                                <th class="tw-px-6 tw-py-3">Kode Transaksi</th>
                                                <th class="tw-px-6 tw-py-3">Jenis Transaksi</th>
                                                <th class="tw-px-6 tw-py-3 tw-text-right">Debit</th>
                                                <th class="tw-px-6 tw-py-3 tw-text-right">Kredit</th>
                                                <th class="tw-px-6 tw-py-3 tw-text-right">Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($transaksiList as $transaksi)
                                                <tr class="tw-border-b dark:tw-border-slate-700 hover:tw-bg-slate-50 dark:hover:tw-bg-slate-800/30">
                                                    <td class="tw-px-6 tw-py-4 tw-font-medium">
                                                        {{ $transaksi->created_at->format('d/m/Y') }}
                                                    </td>
                                                    <td class="tw-px-6 tw-py-4 tw-text-xs tw-font-mono">
                                                        {{ $transaksi->kode_transaksi }}
                                                    </td>
                                                    <td class="tw-px-6 tw-py-4">
                                                        @if($transaksi->jenis_transaksi == 'simpanan')
                                                            <span class="tw-inline-flex tw-items-center tw-gap-1 tw-text-green-600 dark:tw-text-green-400">
                                                                <span class="material-symbols-outlined tw-text-sm">arrow_downward</span>
                                                                Setoran
                                                            </span>
                                                        @else
                                                            <span class="tw-inline-flex tw-items-center tw-gap-1 tw-text-red-600 dark:tw-text-red-400">
                                                                <span class="material-symbols-outlined tw-text-sm">arrow_upward</span>
                                                                Penarikan
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-text-red-600 dark:tw-text-red-400 tw-font-medium">
                                                        @if($transaksi->jenis_transaksi == 'penarikan_simpanan')
                                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                                        @else
                                                            Rp 0
                                                        @endif
                                                    </td>
                                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-text-green-600 dark:tw-text-green-400 tw-font-medium">
                                                        @if($transaksi->jenis_transaksi == 'simpanan')
                                                            Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                                        @else
                                                            Rp 0
                                                        @endif
                                                    </td>
                                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-font-semibold tw-text-slate-900 dark:tw-text-white">
                                                        Rp {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="tw-p-8 tw-text-center">
                                    <span class="material-symbols-outlined tw-text-5xl tw-text-slate-300 dark:tw-text-slate-600 tw-mb-2">
                                        receipt_long
                                    </span>
                                    <p class="tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                        Belum ada riwayat transaksi
                                    </p>
                                </div>
                            @endif

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
