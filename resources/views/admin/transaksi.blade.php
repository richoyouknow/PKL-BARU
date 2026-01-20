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
                            Riwayat Transaksi Umum
                        </h1>
                        <p class="tw-text-gray-500 dark:tw-text-gray-400">
                            Lihat semua transaksi keuangan Anda di satu tempat.
                        </p>
                    </div>

                    <button class="tw-flex tw-h-10 tw-items-center tw-gap-x-2 tw-rounded-lg tw-bg-primary tw-px-4 tw-text-sm tw-font-bold tw-text-white">
                        <span class="material-symbols-outlined tw-text-base">download</span>
                        Export CSV
                    </button>
                </div>

                <!-- FILTERS -->
                <div class="tw-mb-6 tw-rounded-xl tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50 tw-p-4">

                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 lg:tw-grid-cols-4 tw-gap-4 tw-items-end">

                        <div class="tw-flex tw-flex-col tw-gap-1.5">
                            <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Rentang Tanggal</label>
                            <button class="tw-flex tw-h-12 tw-items-center tw-justify-between tw-rounded-lg tw-border tw-bg-background-light tw-px-4 tw-text-sm tw-text-gray-700 dark:tw-bg-gray-800 dark:tw-text-gray-300">
                                <span>Pilih Tanggal</span>
                                <span class="material-symbols-outlined tw-text-lg">calendar_today</span>
                            </button>
                        </div>

                        <div class="tw-flex tw-flex-col tw-gap-1.5">
                            <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Jenis Transaksi</label>
                            <select class="tw-form-select tw-h-12 tw-rounded-lg tw-border tw-bg-background-light dark:tw-bg-gray-800 tw-text-gray-700 dark:tw-text-gray-300">
                                <option>Semua</option>
                                <option>Setoran</option>
                                <option>Penarikan</option>
                                <option>Pinjaman</option>
                            </select>
                        </div>

                        <div class="tw-flex tw-flex-col tw-gap-1.5 lg:tw-col-span-2">
                            <label class="tw-text-sm tw-font-medium tw-text-gray-700 dark:tw-text-gray-300">Cari Deskripsi</label>

                            <div class="tw-relative tw-flex">
                                <span class="material-symbols-outlined tw-absolute tw-left-4 tw-top-1/2 -tw-translate-y-1/2 tw-text-gray-400">
                                    search
                                </span>

                                <input class="tw-form-input tw-h-12 tw-w-full tw-rounded-lg tw-border tw-bg-background-light dark:tw-bg-gray-800 tw-pl-11 tw-pr-4 tw-text-base tw-text-gray-800 dark:tw-text-gray-200 tw-placeholder-gray-400"
                                       placeholder="Cari berdasarkan deskripsi...">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="tw-overflow-x-auto tw-rounded-xl tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-900/50">

                    <table class="tw-min-w-full tw-divide-y tw-divide-gray-200 dark:tw-divide-gray-700">

                        <thead class="tw-bg-gray-50 dark:tw-bg-gray-800">
                            <tr>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400">
                                    <div class="tw-flex tw-items-center tw-gap-1">
                                        Tanggal
                                        <span class="material-symbols-outlined tw-text-base">swap_vert</span>
                                    </div>
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400">
                                    Jenis Transaksi
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400">
                                    Deskripsi
                                </th>
                                <th class="tw-px-6 tw-py-3 tw-text-right tw-text-xs tw-font-bold tw-uppercase tw-text-gray-500 dark:tw-text-gray-400">
                                    <div class="tw-flex tw-justify-end tw-gap-1 tw-items-center">
                                        Jumlah
                                        <span class="material-symbols-outlined tw-text-base">swap_vert</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="tw-divide-y tw-divide-gray-200 dark:tw-divide-gray-700">

                            <tr>
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-text-gray-600 dark:tw-text-gray-300">15 Agu 2023</td>
                                <td class="tw-px-6 tw-py-4">
                                    <span class="tw-inline-flex tw-items-center tw-rounded-md tw-bg-green-100 dark:tw-bg-green-900/50 tw-px-2.5 tw-py-1 tw-text-xs tw-font-semibold tw-text-green-800 dark:tw-text-green-300">
                                        Setoran Tunai
                                    </span>
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-sm tw-font-medium tw-text-gray-800 dark:tw-text-gray-200">
                                    Setoran bulanan
                                </td>
                                <td class="tw-px-6 tw-py-4 tw-text-right tw-text-sm tw-font-semibold tw-text-green-600 dark:tw-text-green-400">
                                    + Rp 500.000
                                </td>
                            </tr>

                            <!-- dst (semua baris sudah tw-) -->
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="tw-mt-6 tw-flex tw-items-center tw-justify-between">
                    <span class="tw-text-sm tw-text-gray-600 dark:tw-text-gray-400">
                        Menampilkan <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">1</span>-
                        <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">6</span>
                        dari <span class="tw-font-semibold tw-text-gray-900 dark:tw-text-white">24</span>
                    </span>

                    <div class="tw-flex tw-items-center tw-gap-2">

                        <button class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-800 tw-text-gray-500 hover:tw-bg-gray-100 dark:hover:tw-bg-gray-700">
                            <span class="material-symbols-outlined tw-text-lg">chevron_left</span>
                        </button>

                        <button class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-primary tw-bg-primary/20 tw-text-primary">
                            1
                        </button>

                        <button class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-800 tw-text-gray-500">
                            2
                        </button>

                        <button class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-800 tw-text-gray-500">
                            3
                        </button>

                        <button class="tw-flex tw-h-9 tw-w-9 tw-items-center tw-justify-center tw-rounded-lg tw-border tw-border-gray-200 dark:tw-border-gray-700 tw-bg-white dark:tw-bg-gray-800 tw-text-gray-500">
                            <span class="material-symbols-outlined tw-text-lg">chevron_right</span>
                        </button>

                    </div>
                </div>

            </div>
        </main>

    </div>

</div>


@endsection
