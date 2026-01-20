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

        .mask-linear-fade {
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }

        .status-badge-warning {
            background-color: rgb(254 252 232);
            color: rgb(161 98 7);
            border-color: rgb(253 224 71);
        }

        .status-badge-info {
            background-color: rgb(224 242 254);
            color: rgb(3 105 161);
            border-color: rgb(125 211 252);
        }

        .status-badge-success {
            background-color: rgb(220 252 231);
            color: rgb(22 101 52);
            border-color: rgb(134 239 172);
        }

        .status-badge-danger {
            background-color: rgb(254 226 226);
            color: rgb(153 27 27);
            border-color: rgb(252 165 165);
        }

        .status-badge-primary {
            background-color: rgb(219 234 254);
            color: rgb(30 58 138);
            border-color: rgb(147 197 253);
        }

        .status-badge-secondary {
            background-color: rgb(241 245 249);
            color: rgb(71 85 105);
            border-color: rgb(203 213 225);
        }

        .dark .status-badge-warning {
            background-color: rgb(120 53 15 / 0.3);
            color: rgb(253 224 71);
            border-color: rgb(251 191 36 / 0.5);
        }

        .dark .status-badge-info {
            background-color: rgb(12 74 110 / 0.3);
            color: rgb(125 211 252);
            border-color: rgb(56 189 248 / 0.5);
        }

        .dark .status-badge-success {
            background-color: rgb(21 128 61 / 0.3);
            color: rgb(134 239 172);
            border-color: rgb(74 222 128 / 0.5);
        }

        .dark .status-badge-danger {
            background-color: rgb(127 29 29 / 0.3);
            color: rgb(252 165 165);
            border-color: rgb(248 113 113 / 0.5);
        }

        .dark .status-badge-primary {
            background-color: rgb(30 58 138 / 0.3);
            color: rgb(147 197 253);
            border-color: rgb(96 165 250 / 0.5);
        }

        .dark .status-badge-secondary {
            background-color: rgb(30 41 59 / 0.3);
            color: rgb(203 213 225);
            border-color: rgb(148 163 184 / 0.5);
        }
    </style>
@endpush

@section('content')
    <div class="tw-relative tw-flex tw-h-full tw-w-full tw-flex-col group/design-root tw-overflow-x-hidden">
        <div class="tw-layout-container tw-flex tw-h-full tw-grow tw-flex-col">
            <div class="tw-mx-auto tw-w-full tw-max-w-7xl tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-6 md:tw-py-8">
                <div class="tw-flex tw-flex-col tw-gap-6 md:tw-gap-8">
                    <div class="tw-flex tw-flex-wrap tw-justify-between tw-items-end tw-gap-4">
                        <div class="tw-flex tw-flex-col tw-gap-2">
                            <h1
                                class="tw-text-slate-900 dark:tw-text-white tw-text-2xl md:tw-text-3xl lg:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-[-0.033em]">
                                Dashboard Pinjaman Anda
                            </h1>
                            <div class="tw-flex tw-items-center tw-gap-2 tw-text-slate-500 dark:tw-text-slate-400">
                                <span class="material-symbols-outlined tw-text-lg">account_balance</span>
                                <p class="tw-text-sm md:tw-text-base tw-font-medium tw-leading-normal">Koperasi Daun Emas Nusantara</p>
                            </div>
                        </div>
                        <div class="tw-text-right tw-hidden md:tw-block">
                            <p class="tw-text-sm tw-font-medium tw-text-slate-500 dark:tw-text-slate-400">Selamat Datang, Anggota
                            </p>
                            <p class="tw-text-sm tw-font-bold tw-text-slate-900 dark:tw-text-white">{{ $anggota->nama ?? 'Anggota' }}</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="tw-rounded-lg tw-bg-green-50 dark:tw-bg-green-900/30 tw-p-4 tw-border tw-border-green-200 dark:tw-border-green-800">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-flex-shrink-0">
                                    <span class="material-symbols-outlined tw-text-green-400">check_circle</span>
                                </div>
                                <div class="tw-ml-3">
                                    <p class="tw-text-sm tw-font-medium tw-text-green-800 dark:tw-text-green-300">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="tw-rounded-lg tw-bg-red-50 dark:tw-bg-red-900/30 tw-p-4 tw-border tw-border-red-200 dark:tw-border-red-800">
                            <div class="tw-flex tw-items-center">
                                <div class="tw-flex-shrink-0">
                                    <span class="material-symbols-outlined tw-text-red-400">error</span>
                                </div>
                                <div class="tw-ml-3">
                                    <p class="tw-text-sm tw-font-medium tw-text-red-800 dark:tw-text-red-300">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-3 tw-gap-4">
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div
                                    class="tw-p-2 tw-bg-green-100 dark:tw-bg-green-900/30 tw-rounded-full tw-text-green-700 dark:tw-text-green-400">
                                    <span class="material-symbols-outlined tw-text-xl">account_balance_wallet</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Total Sisa Pinjaman</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                Rp {{ number_format($total_sisa_pinjaman, 0, ',', '.') }}</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">Dari total plafon Rp
                                {{ number_format($limit_maksimal, 0, ',', '.') }}</p>
                        </div>
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div
                                    class="tw-p-2 tw-bg-blue-100 dark:tw-bg-blue-900/30 tw-rounded-full tw-text-blue-700 dark:tw-text-blue-400">
                                    <span class="material-symbols-outlined tw-text-xl">assignment</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Pinjaman Aktif</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                {{ $pinjaman_aktif }} Kontrak</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                @if(!empty($kategori_aktif))
                                    @foreach($kategori_aktif as $kategori => $total)
                                        {{ $total }} {{ $kategori }},
                                    @endforeach
                                @else
                                    Tidak ada pinjaman aktif
                                @endif
                            </p>
                        </div>
                        <div
                            class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-5 md:tw-p-6 tw-bg-surface-light dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark tw-shadow-sm">
                            <div class="tw-flex tw-items-center tw-gap-2 tw-mb-1">
                                <div class="tw-p-2 tw-bg-primary/20 tw-rounded-full tw-text-green-800 dark:tw-text-primary">
                                    <span class="material-symbols-outlined tw-text-xl">verified</span>
                                </div>
                                <p
                                    class="tw-text-slate-600 dark:tw-text-slate-300 tw-text-xs md:tw-text-sm tw-font-bold tw-uppercase tw-tracking-wider">
                                    Status Kolektibilitas</p>
                            </div>
                            <p
                                class="tw-text-slate-900 dark:tw-text-white tw-tracking-tight tw-text-2xl md:tw-text-3xl tw-font-bold tw-leading-tight">
                                {{ $status_kolektibilitas['label'] ?? 'Tidak Diketahui' }}</p>
                            <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">{{ $status_kolektibilitas['description'] ?? '' }}</p>
                        </div>
                    </div>

                    <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start md:tw-items-center tw-gap-4">
                        <div class="tw-flex tw-gap-2 tw-overflow-x-auto tw-pb-2 md:tw-pb-0 tw-w-full md:tw-w-auto no-scrollbar mask-linear-fade">
                            <a href="{{ route('pinjaman.index') }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ !request()->has('status') || request('status') == '' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-bold">Semua</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'aktif']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'aktif' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Aktif</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'lunas']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'lunas' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Lunas</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'diajukan']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'diajukan' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Diajukan</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'diproses']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'diproses' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Diproses</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'disetujui']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'disetujui' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Disetujui</p>
                            </a>
                            <a href="{{ route('pinjaman.index', ['status' => 'macet']) }}"
                                class="tw-flex tw-h-10 tw-shrink-0 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-full {{ request('status') == 'macet' ? 'tw-bg-slate-900 dark:tw-bg-white tw-text-white dark:tw-text-slate-900' : 'tw-bg-white dark:tw-bg-surface-dark tw-border tw-border-border-light dark:tw-border-border-dark hover:tw-bg-slate-50 dark:hover:tw-bg-white/5' }} tw-px-5 tw-transition-colors">
                                <p class="tw-text-sm tw-font-medium">Macet</p>
                            </a>
                        </div>
                        <a href="{{ route('pinjaman.create') }}"
                            class="tw-group tw-flex tw-min-w-[180px] tw-w-full md:tw-w-auto tw-cursor-pointer tw-items-center tw-justify-center tw-overflow-hidden tw-rounded-lg tw-h-12 tw-px-6 tw-bg-primary hover:tw-bg-green-400 tw-text-slate-900 tw-gap-2 tw-text-base tw-font-bold tw-leading-normal tw-tracking-[0.015em] tw-transition-all tw-shadow-md hover:tw-shadow-lg tw-shadow-primary/20">
                            <span
                                class="material-symbols-outlined tw-text-slate-900 group-hover:tw-scale-110 tw-transition-transform">add_circle</span>
                            <span class="tw-truncate">Ajukan Pinjaman Baru</span>
                        </a>
                    </div>

                    <div class="@container tw-w-full">
                        @if($pinjamans->isEmpty())
                            <div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-py-12 tw-text-center">
                                <div class="tw-rounded-full tw-bg-slate-100 dark:tw-bg-slate-800 tw-p-6 tw-mb-4">
                                    <span class="material-symbols-outlined tw-text-4xl tw-text-slate-400">account_balance_wallet</span>
                                </div>
                                <h3 class="tw-text-lg tw-font-bold tw-text-slate-900 dark:tw-text-white tw-mb-2">Belum ada pinjaman</h3>
                                <p class="tw-text-slate-500 dark:tw-text-slate-400 tw-mb-6">Anda belum memiliki pinjaman yang diajukan atau aktif.</p>
                                <a href="{{ route('pinjaman.create') }}" class="tw-rounded-lg tw-bg-primary hover:tw-bg-green-400 tw-px-6 tw-py-3 tw-text-sm tw-font-bold tw-text-slate-900 tw-shadow-sm">
                                    Ajukan Pinjaman Pertama
                                </a>
                            </div>
                        @else
                            <div
                                class="tw-hidden md:tw-flex tw-flex-col tw-overflow-hidden tw-rounded-xl tw-border tw-border-border-light dark:tw-border-border-dark tw-bg-surface-light dark:tw-bg-surface-dark tw-shadow-sm">
                                <div class="tw-overflow-x-auto">
                                    <table class="tw-w-full tw-min-w-[800px]">
                                        <thead
                                            class="tw-bg-slate-50 dark:tw-bg-white/5 tw-border-b tw-border-border-light dark:tw-border-border-dark">
                                            <tr>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-left tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider">
                                                    Jenis Pinjaman</th>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-left tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider">
                                                    Jumlah Awal</th>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-left tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider">
                                                    Sisa Saldo</th>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-left tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider tw-w-[200px]">
                                                    Sisa Tenor</th>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-left tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider">
                                                    Jatuh Tempo</th>
                                                <th
                                                    class="tw-px-6 tw-py-4 tw-text-right tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs tw-font-bold tw-uppercase tw-tracking-wider">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tw-divide-y tw-divide-border-light dark:tw-divide-border-dark">
                                            @foreach($pinjamans as $pinjaman)
                                            <tr class="tw-group hover:tw-bg-slate-50 dark:hover:tw-bg-white/5 tw-transition-colors">
                                                <td class="tw-px-6 tw-py-5">
                                                    <a href="{{ route('pinjaman.show', $pinjaman->id) }}" class="tw-flex tw-items-center tw-gap-3 hover:tw-opacity-80 tw-transition-opacity">
                                                        <div
                                                            class="tw-flex tw-h-10 tw-w-10 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-lg tw-bg-blue-100 dark:tw-bg-blue-900/30 tw-text-blue-700 dark:tw-text-blue-400">
                                                            <span class="material-symbols-outlined">
                                                                @if($pinjaman->kategori_pinjaman == 'pinjaman_cash')
                                                                    account_balance_wallet
                                                                @else
                                                                    devices
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="tw-flex tw-flex-col">
                                                            <p class="tw-text-slate-900 dark:tw-text-white tw-text-sm tw-font-bold">
                                                                {{ $pinjaman->kategori_pinjaman_label }}
                                                            </p>
                                                            <p class="tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs">ID:
                                                                {{ $pinjaman->no_pinjaman }}</p>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td class="tw-px-6 tw-py-5">
                                                    <p class="tw-text-slate-700 dark:tw-text-slate-300 tw-text-sm tw-font-medium">Rp
                                                        {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</p>
                                                </td>
                                                <td class="tw-px-6 tw-py-5">
                                                    <p class="tw-text-slate-900 dark:tw-text-white tw-text-sm tw-font-bold">Rp {{ number_format($pinjaman->saldo_pinjaman, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                    <td class="tw-px-6 tw-py-5">
                                                        <div class="tw-flex tw-flex-col tw-gap-2">
                                                            <div class="tw-flex tw-items-center tw-gap-2">
                                                                <span class="material-symbols-outlined tw-text-base tw-text-slate-500 dark:tw-text-slate-400">event</span>
                                                                <span class="tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">Sisa Tenor:</span>
                                                            </div>
                                                            <div class="tw-flex tw-items-center tw-gap-2">
                                                                <span class="tw-inline-flex tw-items-center tw-px-3 tw-py-1 tw-rounded-full tw-text-sm tw-font-bold
                                                                    @if($pinjaman->sisa_tenor == 0)
                                                                        tw-bg-green-100 tw-text-green-700 dark:tw-bg-green-900/30 dark:tw-text-green-400
                                                                    @elseif($pinjaman->sisa_tenor <= 3)
                                                                        tw-bg-yellow-100 tw-text-yellow-700 dark:tw-bg-yellow-900/30 dark:tw-text-yellow-400
                                                                    @else
                                                                        tw-bg-blue-100 tw-text-blue-700 dark:tw-bg-blue-900/30 dark:tw-text-blue-400
                                                                    @endif">
                                                                    {{ $pinjaman->sisa_tenor }}/{{ $pinjaman->tenor }} bulan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                <td class="tw-px-6 tw-py-5">
                                                    <div class="tw-flex tw-items-center tw-gap-2
                                                        @if($pinjaman->tanggal_jatuh_tempo->isPast() && $pinjaman->status != 'lunas')
                                                            tw-text-red-600 dark:tw-text-red-400
                                                        @else
                                                            tw-text-slate-700 dark:tw-text-slate-300
                                                        @endif">
                                                        <span class="material-symbols-outlined tw-text-base">calendar_today</span>
                                                        <span class="tw-text-sm">{{ $pinjaman->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                                    </div>
                                                </td>
                                                <td class="tw-px-6 tw-py-5 tw-text-right">
                                                    @php
                                                        $badgeClass = 'status-badge-' . $pinjaman->status_badge;
                                                    @endphp
                                                    <span
                                                        class="tw-inline-flex tw-items-center tw-rounded-md tw-px-3 tw-py-1 tw-text-xs tw-font-bold tw-ring-1 tw-ring-inset {{ $badgeClass }}">
                                                        {{ ucfirst($pinjaman->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tw-flex tw-flex-col tw-gap-4 md:tw-hidden">
                                @foreach($pinjamans as $pinjaman)
                                <div
                                    class="tw-flex tw-flex-col tw-gap-4 tw-rounded-xl tw-border tw-border-border-light dark:tw-border-border-dark tw-bg-surface-light dark:tw-bg-surface-dark tw-p-5 tw-shadow-sm">
                                    <div class="tw-flex tw-justify-between tw-items-start tw-gap-4">
                                        <a href="{{ route('pinjaman.show', $pinjaman->id) }}" class="tw-flex tw-items-center tw-gap-3 hover:tw-opacity-80 tw-transition-opacity">
                                            <div
                                                class="tw-flex tw-h-10 tw-w-10 tw-shrink-0 tw-items-center tw-justify-center tw-rounded-lg tw-bg-blue-100 dark:tw-bg-blue-900/30 tw-text-blue-700 dark:tw-text-blue-400">
                                                <span class="material-symbols-outlined">
                                                    @if($pinjaman->kategori_pinjaman == 'pinjaman_cash')
                                                        account_balance_wallet
                                                    @else
                                                        devices
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="tw-flex tw-flex-col">
                                                <p class="tw-text-slate-900 dark:tw-text-white tw-text-sm tw-font-bold">
                                                    {{ $pinjaman->kategori_pinjaman_label }}
                                                </p>
                                                <p class="tw-text-slate-500 dark:tw-text-slate-400 tw-text-xs">ID: {{ $pinjaman->no_pinjaman }}</p>
                                            </div>
                                        </a>
                                        @php
                                            $badgeClass = 'status-badge-' . $pinjaman->status_badge;
                                        @endphp
                                        <span
                                            class="tw-inline-flex tw-items-center tw-rounded-md tw-px-2.5 tw-py-1 tw-text-xs tw-font-bold tw-ring-1 tw-ring-inset {{ $badgeClass }} tw-shrink-0">
                                            {{ ucfirst($pinjaman->status) }}
                                        </span>
                                    </div>
                                    <div
                                        class="tw-grid tw-grid-cols-2 tw-gap-4 tw-border-t tw-border-b tw-border-border-light dark:tw-border-border-dark tw-py-3">
                                        <div class="tw-flex tw-flex-col tw-gap-1">
                                            <p
                                                class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-uppercase tw-font-bold tw-tracking-wider">
                                                Jumlah Awal</p>
                                            <p class="tw-text-slate-700 dark:tw-text-slate-300 tw-text-sm tw-font-medium">Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        <div class="tw-flex tw-flex-col tw-gap-1">
                                            <p
                                                class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-uppercase tw-font-bold tw-tracking-wider">
                                                Sisa Saldo</p>
                                            <p class="tw-text-slate-900 dark:tw-text-white tw-text-sm tw-font-bold">Rp {{ number_format($pinjaman->saldo_pinjaman, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="tw-flex tw-flex-col tw-gap-3">
                                        <div class="tw-flex tw-flex-col tw-gap-2">
                                            <div class="tw-flex tw-justify-between tw-items-center tw-text-xs">
                                                <span class="tw-text-slate-500 dark:tw-text-slate-400">Progres Pembayaran</span>
                                                <span class="tw-text-slate-900 dark:tw-text-white tw-font-bold">{{ number_format($pinjaman->persentase_lunas, 1) }}%</span>
                                            </div>
                                            <div class="tw-h-2 tw-w-full tw-overflow-hidden tw-rounded-full tw-bg-slate-100 dark:tw-bg-white/10">
                                                <div class="tw-h-full tw-rounded-full
                                                    @if($pinjaman->persentase_lunas == 100)
                                                        tw-bg-slate-400 dark:tw-bg-slate-600
                                                    @else
                                                        tw-bg-primary
                                                    @endif"
                                                    style="width: {{ min($pinjaman->persentase_lunas, 100) }}%;"></div>
                                            </div>
                                        </div>
                                        <div class="tw-flex tw-items-center tw-justify-between tw-text-sm">
                                            <span class="tw-text-slate-500 dark:tw-text-slate-400">Jatuh Tempo:</span>
                                            <div
                                                class="tw-flex tw-items-center tw-gap-1.5
                                                    @if($pinjaman->tanggal_jatuh_tempo->isPast() && $pinjaman->status != 'lunas')
                                                        tw-text-red-600 dark:tw-text-red-400
                                                    @else
                                                        tw-text-slate-700 dark:tw-text-slate-300
                                                    @endif tw-font-medium">
                                                <span class="material-symbols-outlined tw-text-base">calendar_today</span>
                                                <span>{{ $pinjaman->tanggal_jatuh_tempo->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="tw-flex tw-justify-center tw-mt-4">
                        <p class="tw-text-slate-400 dark:tw-text-slate-500 tw-text-xs md:tw-text-sm tw-text-center tw-px-4">
                            Butuh bantuan? <a class="tw-text-primary hover:tw-underline tw-font-medium" href="#">Hubungi
                                Layanan Anggota</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
