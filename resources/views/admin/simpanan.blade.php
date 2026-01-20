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


    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }
    </style>
@endpush

@section('content')
    <!-- About Start -->

    <div id="tailwind-scope">
        <div class="tw-relative tw-min-h-screen tw-w-full tw-bg-background-light dark:tw-bg-background-dark tw-py-10">
            <div class="tw-max-w-7xl tw-mx-auto tw-px-4">
                <!-- HEADER -->

                    <div
                        class="tw-flex tw-w-full tw-items-center tw-gap-3 tw-rounded-xl tw-border tw-border-slate-200 tw-bg-white tw-p-2 tw-shadow-sm dark:tw-border-slate-700 dark:tw-bg-slate-800">
                        <div class="tw-flex tw-h-10 tw-w-10 tw-items-center tw-justify-center tw-text-slate-400">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input
                            class="tw-flex-1 tw-border-none tw-bg-transparent tw-px-0 tw-text-sm tw-font-medium tw-text-slate-900 tw-placeholder-slate-400 focus:tw-ring-0 dark:tw-text-white"
                            placeholder="Cari anggota berdasarkan nama, nomor anggota, atau nomor identitas..."
                            type="text" />
                        <button
                            class="tw-h-10 tw-cursor-pointer tw-rounded-lg tw-bg-primary tw-px-6 tw-text-sm tw-font-bold tw-text-white hover:tw-bg-primary/90">
                            Cari
                        </button>
                    </div>


                <div class="tw-flex tw-flex-wrap tw-justify-between tw-items-start tw-gap-4 tw-mb-8 tw-mt-8 animated slideInDown">
                    <div>
                        <h1 class="tw-text-4xl tw-font-black tw-text-slate-900 dark:tw-text-slate-50">
                            Detail Simpanan Anggota
                        </h1>
                        <p class="tw-text-slate-500 dark:tw-text-slate-400">
                            Lihat rincian lengkap simpanan dan riwayat transaksi Anda.
                        </p>
                    </div>

                    <div class="tw-flex tw-gap-3">

                        <button
                            class="tw-h-10 tw-px-4 tw-rounded-lg tw-bg-slate-200 dark:tw-bg-slate-700 tw-text-sm tw-font-semibold tw-flex tw-items-center tw-gap-2">
                            <span class="material-symbols-outlined tw-text-lg">download</span>
                            Download PDF
                        </button>

                        <button
                            class="tw-h-10 tw-px-4 tw-rounded-lg tw-bg-primary tw-text-white tw-text-sm tw-font-semibold tw-flex tw-items-center tw-gap-2">
                            <span class="material-symbols-outlined tw-text-lg">print</span>
                            Cetak Laporan
                        </button>

                    </div>
                </div>

                <!-- DETAIL SIMPANAN -->
                <div
                    class="tw-bg-white dark:tw-bg-slate-800/50 tw-rounded-xl tw-p-6 tw-shadow-sm tw-border tw-border-slate-200 dark:tw-border-slate-700 animated fadeInUp">

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-3 tw-gap-4">

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Tanggal</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">23/10/2023</p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">No Rek</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">0123456789</p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Produk Simpanan</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">Simpanan Wajib</p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Nomor Anggota</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">KSP-001</p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Nama Anggota</p>
                            <p>{{ $anggota->nama }}</p>

                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">No Identitas</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">3301010101010001
                            </p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4 tw-col-span-2 lg:tw-col-span-1">
                            <p class="tw-text-slate-500 tw-text-sm">Alamat</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">
                                Jl. Merdeka No. 12, Jakarta
                            </p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Status Anggota</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">Aktif</p>
                        </div>

                        <div class="tw-flex tw-flex-col tw-border-t tw-py-4">
                            <p class="tw-text-slate-500 tw-text-sm">Tanggal Daftar Rekening</p>
                            <p class="tw-text-slate-800 dark:tw-text-slate-200 tw-text-sm tw-font-medium">01/01/2020</p>
                        </div>

                        <div
                            class="tw-flex tw-flex-col tw-border-t tw-py-4 tw-bg-primary/10 dark:tw-bg-primary/20 tw-rounded-lg tw-col-span-2 tw-p-4">
                            <p class="tw-text-slate-500 dark:tw-text-slate-300 tw-text-sm">Saldo Terakhir</p>
                            <p class="tw-text-primary dark:tw-text-sky-300 tw-text-2xl tw-font-bold">Rp 15.000.000</p>
                        </div>

                    </div>
                </div>

                <!-- RIWAYAT TRANSAKSI -->
                <h2
                    class="tw-text-xl tw-font-bold tw-text-slate-900 dark:tw-text-slate-50 tw-mt-10 tw-mb-3 animated fadeInUp">
                    Riwayat Transaksi
                </h2>

                <div
                    class="tw-bg-white dark:tw-bg-slate-800/50 tw-rounded-xl tw-shadow-sm tw-border tw-border-slate-200 dark:tw-border-slate-700 tw-overflow-hidden animated fadeInUp">

                    <div class="tw-overflow-x-auto">

                        <table class="tw-w-full tw-text-sm tw-text-left">

                            <thead
                                class="tw-bg-slate-100 dark:tw-bg-slate-800 tw-text-slate-700 dark:tw-text-slate-300 tw-uppercase">
                                <tr>
                                    <th class="tw-px-6 tw-py-3">Tanggal</th>
                                    <th class="tw-px-6 tw-py-3">Jenis Transaksi</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-right">Debit</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-right">Kredit</th>
                                    <th class="tw-px-6 tw-py-3 tw-text-right">Saldo</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr class="tw-border-b dark:tw-border-slate-700">
                                    <td class="tw-px-6 tw-py-4 tw-font-medium">20/10/2023</td>
                                    <td class="tw-px-6 tw-py-4">Setoran Tunai</td>
                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-text-red-600">Rp 0</td>
                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-text-green-600">Rp 1.000.000</td>
                                    <td class="tw-px-6 tw-py-4 tw-text-right tw-font-semibold">Rp 15.000.000</td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- About End -->
@endsection
