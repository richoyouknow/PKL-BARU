@extends('layout.master')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
                prefix: 'tw-',
                darkMode: "class",
                important: "#tailwind-scope",
                theme: { // Hindari bentrok dengan .container Bootstrap
                        extend: {
                            colors: {
                                "primary": "#197fe6",
                                "background-light": "#f6f7f8",
                                "background-dark": "#111921",
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

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
@endpush

@section('content')
    <div id="tailwind-scope">
        <div class="tw-relative tw-flex tw-h-auto tw-min-h-screen tw-w-full tw-flex-col group/design-root tw-overflow-x-hidden">
            <div class="layout-container tw-flex tw-h-full tw-grow tw-flex-col">
                <main class="tw-px-4 sm:tw-px-6 lg:tw-px-20 tw-flex tw-flex-1 tw-justify-center tw-py-8 md:tw-py-12">
                    <div class="layout-content-container tw-flex tw-flex-col tw-w-full tw-max-w-7xl tw-flex-1 tw-gap-8">
                        <div class="tw-flex tw-flex-col tw-gap-4">
                            <div class="tw-flex tw-flex-wrap tw-justify-between tw-gap-4 tw-items-end">
                                <div class="tw-flex tw-min-w-72 tw-flex-col tw-gap-2">
                                    <p
                                        class="tw-text-slate-900 dark:tw-text-slate-50 tw-text-3xl md:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-[-0.033em]">
                                        Detail Pinjaman #PJN00123</p>
                                    <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-normal tw-leading-normal">
                                        Informasi lengkap mengenai status pinjaman dan riwayat pembayaran Anda.</p>
                                </div>
                                <div class="tw-flex tw-gap-2">
                                    <button
                                        class="tw-flex tw-min-w-[84px] tw-cursor-pointer tw-items-center tw-justify-center tw-overflow-hidden tw-rounded-lg tw-h-10 tw-px-4 tw-gap-2 tw-bg-white dark:tw-bg-slate-800 tw-text-slate-900 dark:tw-text-slate-50 tw-text-sm tw-font-bold tw-leading-normal tw-tracking-[0.015em] tw-border tw-border-slate-200 dark:tw-border-slate-700 hover:tw-bg-slate-50 dark:hover:tw-bg-slate-700">
                                        <span class="material-symbols-outlined tw-text-base">help</span>
                                        <span class="tw-truncate">Ajukan Pertanyaan</span>
                                    </button>
                                    <button
                                        class="tw-flex tw-min-w-[84px] tw-cursor-pointer tw-items-center tw-justify-center tw-overflow-hidden tw-rounded-lg tw-h-10 tw-px-4 tw-gap-2 tw-bg-primary tw-text-white tw-text-sm tw-font-bold tw-leading-normal tw-tracking-[0.015em] hover:tw-bg-primary/90">
                                        <span class="material-symbols-outlined tw-text-base">print</span>
                                        <span class="tw-truncate">Cetak Laporan</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div
                            class="tw-flex tw-p-6 @container tw-bg-white dark:tw-bg-slate-900 tw-rounded-xl tw-border tw-border-slate-200 dark:tw-border-slate-800">
                            <div
                                class="tw-flex tw-w-full tw-flex-col tw-gap-4 @[520px]:tw-flex-row @[520px]:tw-justify-between @[520px]:tw-items-center">
                                <div class="tw-flex tw-gap-4">
                                    <div class="tw-bg-center tw-bg-no-repeat tw-aspect-square tw-bg-cover tw-rounded-full tw-h-20 tw-w-20 tw-min-w-20"
                                        data-alt="Profile picture of Budi Santoso"
                                        style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAeGKjIfVgBsWf3wAFtMEhpIkwYGaImbcuPCicU-nDAdCB7kvygRTlOmO9H4sUSYBeXJIYEPbZ7tggeKeM4vLLpOgm9LVThUM3p5L_a64qBg74853J73nBvpBEKaorIqgEonjURazkVjaxWiep9Hd0s5FCyLLkYp7MjSsZPvRD4yNsnmDcN9ige7r1FlLBM0TJKaBGQdoacGzcYaGPN8jf5uJbZSll_S166BWx8N3MEcWYx-XNC89XU5ZZS-875s9RfrfoDNq96uT6f");'>
                                    </div>
                                    <div class="tw-flex tw-flex-col tw-justify-center">
                                        <p
                                            class="tw-text-slate-900 dark:tw-text-slate-50 tw-text-xl tw-font-bold tw-leading-tight tw-tracking-[-0.015em]">
                                            Budi Santoso</p>
                                        <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-normal tw-leading-normal">
                                            ID Anggota: KSP-0812</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4">
                            <div
                                class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-6 tw-border tw-border-slate-200 dark:tw-border-slate-800 tw-bg-white dark:tw-bg-slate-900">
                                <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-medium tw-leading-normal">Status
                                    Pinjaman</p>
                                <div class="tw-flex tw-items-center">
                                    <span
                                        class="tw-inline-flex tw-items-center tw-gap-x-1.5 tw-py-1.5 tw-px-3 tw-rounded-full tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800 dark:tw-bg-green-800/30 dark:tw-text-green-500">Aktif</span>
                                </div>
                            </div>
                            <div
                                class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-6 tw-border tw-border-slate-200 dark:tw-border-slate-800 tw-bg-white dark:tw-bg-slate-900">
                                <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-medium tw-leading-normal">Total
                                    Pinjaman</p>
                                <p
                                    class="tw-text-slate-900 dark:tw-text-slate-50 tw-tracking-light tw-text-2xl tw-font-bold tw-leading-tight">
                                    Rp 12.000.000</p>
                            </div>
                            <div
                                class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-6 tw-border tw-border-slate-200 dark:tw-border-slate-800 tw-bg-white dark:tw-bg-slate-900">
                                <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-medium tw-leading-normal">Sisa
                                    Pinjaman</p>
                                <p
                                    class="tw-text-slate-900 dark:tw-text-slate-50 tw-tracking-light tw-text-2xl tw-font-bold tw-leading-tight">
                                    Rp 4.500.000</p>
                            </div>
                            <div
                                class="tw-flex tw-flex-col tw-gap-2 tw-rounded-xl tw-p-6 tw-border tw-border-slate-200 dark:tw-border-slate-800 tw-bg-white dark:tw-bg-slate-900">
                                <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-text-base tw-font-medium tw-leading-normal">Jatuh
                                    Tempo Berikutnya</p>
                                <p
                                    class="tw-text-slate-900 dark:tw-text-slate-50 tw-tracking-light tw-text-2xl tw-font-bold tw-leading-tight">
                                    15 Juli 2024</p>
                            </div>
                        </div>
                        <div class="tw-flex tw-flex-col tw-gap-4">
                            <h3 class="tw-text-xl tw-font-bold tw-text-slate-900 dark:tw-text-slate-50">Riwayat Pembayaran Angsuran</h3>
                            <div class="tw-overflow-x-auto tw-border tw-border-slate-200 dark:tw-border-slate-800 tw-rounded-xl">
                                <table
                                    class="tw-min-w-full tw-divide-y tw-divide-slate-200 dark:tw-divide-slate-800 tw-bg-white dark:tw-bg-slate-900">
                                    <thead class="tw-bg-slate-50 dark:tw-bg-slate-800/50">
                                        <tr>
                                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-text-slate-600 dark:tw-text-slate-400 tw-uppercase tw-tracking-wider"
                                                scope="col">Tanggal Bayar</th>
                                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-text-slate-600 dark:tw-text-slate-400 tw-uppercase tw-tracking-wider"
                                                scope="col">Jumlah Dibayar</th>
                                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-text-slate-600 dark:tw-text-slate-400 tw-uppercase tw-tracking-wider"
                                                scope="col">Saldo Pinjaman</th>
                                            <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-text-slate-600 dark:tw-text-slate-400 tw-uppercase tw-tracking-wider"
                                                scope="col">Metode Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tw-divide-y tw-divide-slate-200 dark:tw-divide-slate-800">
                                        <tr>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                15 Juni 2024</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                Rp 1.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Rp 4.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Transfer Bank</td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                15 Mei 2024</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                Rp 1.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Rp 6.000.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Transfer Bank</td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                15 April 2024</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                Rp 1.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Rp 7.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Tunai</td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                15 Maret 2024</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                Rp 1.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Rp 9.000.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Transfer Bank</td>
                                        </tr>
                                        <tr>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                15 Februari 2024</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-900 dark:tw-text-slate-50">
                                                Rp 1.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Rp 10.500.000</td>
                                            <td
                                                class="tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                                Tunai</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <nav aria-label="Pagination" class="tw-flex tw-items-center tw-justify-between tw-py-3">
                                <div class="tw-hidden sm:tw-block">
                                    <p class="tw-text-sm tw-text-slate-600 dark:tw-text-slate-400">
                                        Menampilkan <span class="tw-font-medium">1</span> sampai <span
                                            class="tw-font-medium">5</span>
                                        dari <span class="tw-font-medium">8</span> hasil
                                    </p>
                                </div>
                                <div class="tw-flex tw-flex-1 tw-justify-between sm:tw-justify-end tw-gap-2">
                                    <a class="tw-relative tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-slate-300 dark:tw-border-slate-700 tw-bg-white dark:tw-bg-slate-900 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-700 dark:tw-text-slate-300 hover:tw-bg-slate-50 dark:hover:tw-bg-slate-800"
                                        href="#">
                                        Sebelumnya
                                    </a>
                                    <a class="tw-relative tw-inline-flex tw-items-center tw-rounded-lg tw-border tw-border-slate-300 dark:tw-border-slate-700 tw-bg-white dark:tw-bg-slate-900 tw-px-4 tw-py-2 tw-text-sm tw-font-medium tw-text-slate-700 dark:tw-text-slate-300 hover:tw-bg-slate-50 dark:hover:tw-bg-slate-800"
                                        href="#">
                                        Berikutnya
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection
