@extends('layout.master')

@push('styles')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            darkMode: "class",
            corePlugins: { preflight: false },
            theme: {
                extend: {
                    colors: {
                        primary: "#197fe6",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111921",
                    },
                    fontFamily: { display: ["Manrope", "sans-serif"] },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" rel="stylesheet" />
    <style>.material-symbols-outlined { font-family: 'Material Symbols Outlined' !important; }</style>
@endpush

@section('content')
<div id="tailwind-scope">
    <div class="tw-relative tw-flex tw-min-h-screen tw-w-full tw-flex-col">
        <main class="tw-p-6 md:tw-p-8 tw-flex-1">
            <div class="tw-mx-auto tw-max-w-4xl">

                <!-- HEADER -->
                <div class="tw-mb-6">
                    <a href="{{ route('anggota.transaksi.index') }}" class="tw-inline-flex tw-items-center tw-gap-2 tw-text-sm tw-font-medium tw-text-gray-600 dark:tw-text-gray-400 hover:tw-text-primary tw-mb-4">
                        <span class="material-symbols-outlined tw-text-lg">arrow_back</span>
                        Kembali ke Riwayat Transaksi
                    </a>
                    <h1 class="tw-text-3xl tw-font-black tw-leading-tight tw-tracking-tight tw-text-gray-900 dark:tw-text-white">
                        Detail Transaksi
                    </h1>
                </div>

                <!-- TRANSACTION DETAILS CARD -->
                <div class="tw-rounded-xl tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-overflow-hidden">

                    <!-- Header -->
                    <div class="tw-bg-gradient-to-r tw-from-primary tw-to-blue-600 tw-p-6">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <p class="tw-text-sm tw-text-blue-100">Kode Transaksi</p>
                                <h2 class="tw-text-2xl tw-font-bold tw-text-white tw-mt-1">{{ $transaksi->kode_transaksi }}</h2>
                            </div>
                            @php
                                $statusColor = match($transaksi->status) {
                                    'sukses' => 'tw-bg-green-500',
                                    'pending' => 'tw-bg-yellow-500',
                                    'gagal' => 'tw-bg-red-500',
                                    'menunggu_verifikasi' => 'tw-bg-blue-500',
                                    default => 'tw-bg-gray-500'
                                };
                            @endphp
                            <span class="tw-inline-flex tw-items-center tw-rounded-full {{ $statusColor }} tw-px-4 tw-py-2 tw-text-sm tw-font-bold tw-text-white">
                                {{ $transaksi->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="tw-p-6 tw-space-y-6">

                        <!-- Amount Section -->
                        <div class="tw-text-center tw-py-6 tw-border-b tw-border-gray-200 dark:tw-border-gray-700">
                            @php
                                $isPositive = in_array($transaksi->jenis_transaksi, ['simpanan', 'pinjaman']);
                                $textColor = $isPositive ? 'tw-text-green-600 dark:tw-text-green-400' : 'tw-text-red-600 dark:tw-text-red-400';
                                $prefix = $isPositive ? '+' : '-';
                            @endphp
                            <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-mb-2">Jumlah Transaksi</p>
                            <h3 class="tw-text-4xl tw-font-black {{ $textColor }}">
                                {{ $prefix }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                            </h3>
                        </div>

                        <!-- Transaction Info Grid -->
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">

                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Jenis Transaksi</p>
                                @php
                                    $badgeColor = match($transaksi->jenis_transaksi) {
                                        'simpanan' => 'tw-bg-green-100 dark:tw-bg-green-900/50 tw-text-green-800 dark:tw-text-green-300',
                                        'penarikan_simpanan' => 'tw-bg-red-100 dark:tw-bg-red-900/50 tw-text-red-800 dark:tw-text-red-300',
                                        'pinjaman' => 'tw-bg-blue-100 dark:tw-bg-blue-900/50 tw-text-blue-800 dark:tw-text-blue-300',
                                        'pembayaran_pinjaman' => 'tw-bg-purple-100 dark:tw-bg-purple-900/50 tw-text-purple-800 dark:tw-text-purple-300',
                                        default => 'tw-bg-gray-100 dark:tw-bg-gray-900/50 tw-text-gray-800 dark:tw-text-gray-300'
                                    };
                                @endphp
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-md {{ $badgeColor }} tw-px-3 tw-py-1.5 tw-text-sm tw-font-semibold">
                                        {{ $transaksi->jenis_transaksi_label }}
                                    </span>
                                </p>
                            </div>

                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Tanggal & Waktu</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    {{ $transaksi->created_at->format('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if($transaksi->simpanan)
                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Jenis Simpanan</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    {{ $transaksi->simpanan->jenis_simpanan ?? '-' }}
                                </p>
                            </div>
                            @endif

                            @if($transaksi->pinjaman)
                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Kode Pinjaman</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    {{ $transaksi->pinjaman->kode_pinjaman ?? '-' }}
                                </p>
                            </div>
                            @endif

                            @if($transaksi->saldo_sebelum)
                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Saldo Sebelum</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    Rp {{ number_format($transaksi->saldo_sebelum, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Saldo Sesudah</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    Rp {{ number_format($transaksi->saldo_sesudah, 0, ',', '.') }}
                                </p>
                            </div>
                            @endif

                            @if($transaksi->diverifikasi_pada)
                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Diverifikasi Pada</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    {{ $transaksi->diverifikasi_pada->format('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if($transaksi->admin)
                            <div class="tw-space-y-1">
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Diverifikasi Oleh</p>
                                <p class="tw-text-base tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                    {{ $transaksi->admin->name ?? '-' }}
                                </p>
                            </div>
                            @endif
                            @endif

                        </div>

                        <!-- Keterangan -->
                        @if($transaksi->keterangan)
                        <div class="tw-pt-6 tw-border-t tw-border-gray-200 dark:tw-border-gray-700">
                            <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400 tw-mb-2">Keterangan</p>
                            <div class="tw-rounded-lg tw-bg-gray-50 dark:tw-bg-gray-800 tw-p-4">
                                <p class="tw-text-sm tw-text-gray-700 dark:tw-text-gray-300 tw-whitespace-pre-line">{{ $transaksi->keterangan }}</p>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="tw-pt-6 tw-border-t tw-border-gray-200 dark:tw-border-gray-700 tw-flex tw-gap-3">
                            <a href="{{ route('anggota.transaksi.index') }}"
                                class="tw-flex-1 tw-flex tw-h-12 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-lg tw-border tw-border-gray-300 dark:tw-border-gray-600 tw-bg-white dark:tw-bg-gray-800 tw-text-sm tw-font-bold tw-text-gray-700 dark:tw-text-gray-300 hover:tw-bg-gray-50 dark:hover:tw-bg-gray-700">
                                <span class="material-symbols-outlined tw-text-base">arrow_back</span>
                                Kembali
                            </a>
                            <button onclick="window.print()"
                                class="tw-flex-1 tw-flex tw-h-12 tw-items-center tw-justify-center tw-gap-x-2 tw-rounded-lg tw-bg-primary tw-text-sm tw-font-bold tw-text-white hover:tw-bg-blue-600">
                                <span class="material-symbols-outlined tw-text-base">print</span>
                                Cetak
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
@media print {
    #tailwind-scope button, #tailwind-scope a[href] {
        display: none !important;
    }
}
</style>
@endsection
