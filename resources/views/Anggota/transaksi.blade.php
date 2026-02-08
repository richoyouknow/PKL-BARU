@extends('layout.master')

@push('styles')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            prefix: 'tw-',
            darkMode: "class",
            corePlugins: {
                preflight: false,
            },
            theme: {
                extend: {
                    colors: {
                        primary: "#197fe6",
                        "background-light": "#f6f7f8",
                        "background-dark": "#111921",
                    },
                    fontFamily: {
                        display: ["Manrope", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" rel="stylesheet" />

    <style>
        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined' !important;
        }
    </style>
@endpush

@section('content')

<div id="tailwind-scope">
    <div class="tw-relative tw-flex tw-min-h-screen tw-w-full tw-flex-col">

        <main class="tw-p-6 md:tw-p-8 tw-flex-1">
            <div class="tw-mx-auto tw-max-w-7xl">

                <!-- HEADER -->
                <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-4 tw-mb-6">
                    <div class="tw-flex tw-flex-col tw-gap-1">
                        <h1 class="tw-text-3xl tw-font-black tw-leading-tight tw-tracking-tight tw-text-gray-900 dark:tw-text-white">
                            Riwayat Transaksi
                        </h1>
                        <p class="tw-text-gray-500 dark:tw-text-gray-400">
                            Lihat semua transaksi simpanan dan pinjaman Anda.
                        </p>
                    </div>

                    <button onclick="exportCSV()" class="tw-flex tw-h-10 tw-items-center tw-gap-x-2 tw-rounded-lg tw-bg-emerald-500 tw-px-4 tw-text-sm tw-font-bold tw-text-white hover:tw-bg-emerald-600 tw-border tw-border-emerald-600">
                        <span class="material-symbols-outlined tw-text-base">download</span>
                        Export CSV
                    </button>
                </div>

                <!-- STATISTICS CARDS -->
                <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-mb-6">
                    <div class="tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-5 tw-shadow-sm">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Total Transaksi</p>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-gray-900 dark:tw-text-white tw-mt-1">{{ $stats['total_transaksi'] }}</h3>
                            </div>
                            <div class="tw-flex tw-h-12 tw-w-12 tw-items-center tw-justify-center tw-rounded-lg tw-bg-blue-100 dark:tw-bg-blue-900/50 tw-border tw-border-blue-200 dark:tw-border-blue-800">
                                <span class="material-symbols-outlined tw-text-2xl tw-text-blue-600 dark:tw-text-blue-400">receipt_long</span>
                            </div>
                        </div>
                    </div>

                    <div class="tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-5 tw-shadow-sm">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Transaksi Sukses</p>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-gray-900 dark:tw-text-white tw-mt-1">{{ $stats['total_sukses'] }}</h3>
                            </div>
                            <div class="tw-flex tw-h-12 tw-w-12 tw-items-center tw-justify-center tw-rounded-lg tw-bg-green-100 dark:tw-bg-green-900/50 tw-border tw-border-green-200 dark:tw-border-green-800">
                                <span class="material-symbols-outlined tw-text-2xl tw-text-green-600 dark:tw-text-green-400">check_circle</span>
                            </div>
                        </div>
                    </div>

                    <div class="tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-5 tw-shadow-sm">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Total Simpanan</p>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-gray-900 dark:tw-text-white tw-mt-1">Rp {{ number_format($stats['total_simpanan'], 0, ',', '.') }}</h3>
                            </div>
                            <div class="tw-flex tw-h-12 tw-w-12 tw-items-center tw-justify-center tw-rounded-lg tw-bg-emerald-100 dark:tw-bg-emerald-900/50 tw-border tw-border-emerald-200 dark:tw-border-emerald-800">
                                <span class="material-symbols-outlined tw-text-2xl tw-text-emerald-600 dark:tw-text-emerald-400">savings</span>
                            </div>
                        </div>
                    </div>

                    <div class="tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-5 tw-shadow-sm">
                        <div class="tw-flex tw-items-center tw-justify-between">
                            <div>
                                <p class="tw-text-sm tw-font-medium tw-text-gray-500 dark:tw-text-gray-400">Total Pinjaman</p>
                                <h3 class="tw-text-2xl tw-font-bold tw-text-gray-900 dark:tw-text-white tw-mt-1">Rp {{ number_format($stats['total_pinjaman'], 0, ',', '.') }}</h3>
                            </div>
                            <div class="tw-flex tw-h-12 tw-w-12 tw-items-center tw-justify-center tw-rounded-lg tw-bg-orange-100 dark:tw-bg-orange-900/50 tw-border tw-border-orange-200 dark:tw-border-orange-800">
                                <span class="material-symbols-outlined tw-text-2xl tw-text-orange-600 dark:tw-text-orange-400">account_balance_wallet</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FILTERS -->
                <form method="GET" action="{{ route('anggota.transaksi.index') }}">
                    <div class="tw-mb-6 tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-4 tw-shadow-sm">
                        <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-5 tw-gap-4 tw-items-end">

                            <div class="tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                                    class="tw-form-input tw-h-12 tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-background-light dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300 focus:tw-border-primary">
                            </div>

                            <div class="tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}"
                                    class="tw-form-input tw-h-12 tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-background-light dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300 focus:tw-border-primary">
                            </div>

                            <div class="tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Jenis Transaksi</label>
                                <select name="jenis_transaksi" class="tw-form-select tw-h-12 tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-background-light dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300 focus:tw-border-primary">
                                    <option value="semua" {{ request('jenis_transaksi') == 'semua' ? 'selected' : '' }}>Semua</option>
                                    <option value="simpanan" {{ request('jenis_transaksi') == 'simpanan' ? 'selected' : '' }}>Simpanan</option>
                                    <option value="penarikan_simpanan" {{ request('jenis_transaksi') == 'penarikan_simpanan' ? 'selected' : '' }}>Penarikan Simpanan</option>
                                    <option value="pinjaman" {{ request('jenis_transaksi') == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                    <option value="pembayaran_pinjaman" {{ request('jenis_transaksi') == 'pembayaran_pinjaman' ? 'selected' : '' }}>Pembayaran Pinjaman</option>
                                </select>
                            </div>

                            <div class="tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Status</label>
                                <select name="status" class="tw-form-select tw-h-12 tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-background-light dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300 focus:tw-border-primary">
                                    <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Semua</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="sukses" {{ request('status') == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                </select>
                            </div>

                            <div class="tw-flex tw-flex-col tw-gap-1.5">
                                <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Cari</label>
                                <div class="tw-relative tw-flex">
                                    <span class="material-symbols-outlined tw-absolute tw-left-4 tw-top-1/2 -tw-translate-y-1/2 tw-text-gray-400">
                                        search
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="tw-form-input tw-h-12 tw-w-full tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-background-light dark:tw-bg-gray-800 tw-pl-11 tw-pr-4 tw-text-base tw-text-gray-800 dark:tw-text-gray-200 tw-placeholder-gray-400 focus:tw-border-primary"
                                        placeholder="Kode atau keterangan...">
                                </div>
                            </div>

                        </div>

                        <div class="tw-flex tw-gap-2 tw-mt-4 tw-pt-4 tw-border-t-2 tw-border-gray-200 dark:tw-border-gray-700">
                            <button type="submit" class="tw-flex tw-h-10 tw-items-center tw-gap-x-2 tw-rounded-lg tw-bg-emerald-500 tw-px-4 tw-text-sm tw-font-bold tw-text-white hover:tw-bg-emerald-600 tw-border tw-border-emerald-600">
                                <span class="material-symbols-outlined tw-text-base">filter_alt</span>
                                Filter
                            </button>
                            <a href="{{ route('anggota.transaksi.index') }}" class="tw-flex tw-h-10 tw-items-center tw-gap-x-2 tw-rounded-lg tw-border-2 tw-border-gray-300 dark:tw-border-gray-600 tw-bg-white dark:tw-bg-gray-800 tw-px-4 tw-text-sm tw-font-bold tw-text-gray-700 dark:tw-text-gray-300 hover:tw-bg-gray-50 dark:hover:tw-bg-gray-700">
                                <span class="material-symbols-outlined tw-text-base">refresh</span>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- TABLE -->
                <div class="tw-overflow-x-auto tw-rounded-xl tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-shadow-sm">
                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 dark:tw-divide-gray-700">
                        <thead class="tw-bg-gray-50 dark:tw-bg-gray-800 tw-border-b-2 tw-border-gray-200 dark:tw-border-gray-700">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Kode Transaksi
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Tanggal
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Jenis Transaksi
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Keterangan
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Jumlah
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-center tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="tw-divide-y tw-divide-gray-200 dark:tw-divide-gray-700">
                            @forelse($transaksis as $transaksi)
                            <tr class="hover:tw-bg-gray-50 dark:hover:tw-bg-gray-800/50">
                                <td class="tw-px-6 tw-py-4 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    <span class="tw-text-sm tw-font-semibold tw-text-gray-900 dark:tw-text-white">
                                        {{ $transaksi->kode_transaksi }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-600 dark:tw-text-gray-300 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    {{ $transaksi->created_at->format('d M Y') }}<br>
                                    <span class="tw-text-xs tw-text-gray-400">{{ $transaksi->created_at->format('H:i') }}</span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    @php
                                        $badgeColor = match($transaksi->jenis_transaksi) {
                                            'simpanan' => 'tw-bg-green-100 dark:tw-bg-green-900/50 tw-text-green-800 dark:tw-text-green-300 tw-border tw-border-green-300 dark:tw-border-green-700',
                                            'penarikan_simpanan' => 'tw-bg-red-100 dark:tw-bg-red-900/50 tw-text-red-800 dark:tw-text-red-300 tw-border tw-border-red-300 dark:tw-border-red-700',
                                            'pinjaman' => 'tw-bg-blue-100 dark:tw-bg-blue-900/50 tw-text-blue-800 dark:tw-text-blue-300 tw-border tw-border-blue-300 dark:tw-border-blue-700',
                                            'pembayaran_pinjaman' => 'tw-bg-purple-100 dark:tw-bg-purple-900/50 tw-text-purple-800 dark:tw-text-purple-300 tw-border tw-border-purple-300 dark:tw-border-purple-700',
                                            default => 'tw-bg-gray-100 dark:tw-bg-gray-900/50 tw-text-gray-800 dark:tw-text-gray-300 tw-border tw-border-gray-300 dark:tw-border-gray-700'
                                        };
                                    @endphp
                                    <span class="tw-inline-flex tw-items-center tw-rounded-md {{ $badgeColor }} tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold">
                                        {{ $transaksi->jenis_transaksi_label }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-800 dark:tw-text-gray-200 tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    {{ Str::limit($transaksi->keterangan ?? '-', 50) }}
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-right tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    @php
                                        $isPositive = in_array($transaksi->jenis_transaksi, ['simpanan', 'pinjaman']);
                                        $textColor = $isPositive ? 'tw-text-green-600 dark:tw-text-green-400' : 'tw-text-red-600 dark:tw-text-red-400';
                                        $prefix = $isPositive ? '+' : '-';
                                    @endphp
                                    <span class="tw-text-sm tw-font-semibold {{ $textColor }}">
                                        {{ $prefix }} Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-center tw-border-r tw-border-gray-200 dark:tw-border-gray-700">
                                    @php
                                        $statusColor = match($transaksi->status) {
                                            'sukses' => 'tw-bg-green-100 dark:tw-bg-green-900/50 tw-text-green-800 dark:tw-text-green-300 tw-border tw-border-green-300 dark:tw-border-green-700',
                                            'pending' => 'tw-bg-yellow-100 dark:tw-bg-yellow-900/50 tw-text-yellow-800 dark:tw-text-yellow-300 tw-border tw-border-yellow-300 dark:tw-border-yellow-700',
                                            'gagal' => 'tw-bg-red-100 dark:tw-bg-red-900/50 tw-text-red-800 dark:tw-text-red-300 tw-border tw-border-red-300 dark:tw-border-red-700',
                                            'menunggu_verifikasi' => 'tw-bg-blue-100 dark:tw-bg-blue-900/50 tw-text-blue-800 dark:tw-text-blue-300 tw-border tw-border-blue-300 dark:tw-border-blue-700',
                                            default => 'tw-bg-gray-100 dark:tw-bg-gray-900/50 tw-text-gray-800 dark:tw-text-gray-300 tw-border tw-border-gray-300 dark:tw-border-gray-700'
                                        };
                                    @endphp
                                    <span class="tw-inline-flex tw-items-center tw-rounded-full {{ $statusColor }} tw-px-3 tw-py-1 tw-text-xs tw-font-semibold">
                                        {{ $transaksi->status_label }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="tw-px-6 tw-py-12 tw-text-center">
                                    <div class="tw-flex tw-flex-col tw-items-center tw-gap-2">
                                        <span class="material-symbols-outlined tw-text-5xl tw-text-gray-300 dark:tw-text-gray-600">receipt_long</span>
                                        <p class="tw-text-gray-500 dark:tw-text-gray-400">Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                @if($transaksis->hasPages())
                <div class="tw-mt-6 tw-flex tw-items-center tw-justify-between tw-p-4 tw-rounded-lg tw-border-2 tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50">
                    <span class="tw-text-sm tw-text-gray-600 dark:tw-text-gray-400">
                        Menampilkan <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">{{ $transaksis->firstItem() }}</span>-
                        <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">{{ $transaksis->lastItem() }}</span>
                        dari <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">{{ $transaksis->total() }}</span>
                    </span>

                    <div class="tw-flex tw-items-center tw-gap-2">
                        {{ $transaksis->links() }}
                    </div>
                </div>
                @endif

            </div>
        </main>

    </div>
</div>

<script>
function exportCSV() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = "{{ route('anggota.transaksi.export') }}?" + params.toString();
}
</script>

@endsection
