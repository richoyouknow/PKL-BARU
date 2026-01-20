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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
        }

        /* Custom styles for input formatting */
        input[name="jumlah_pinjaman"] {
            -moz-appearance: textfield;
        }

        input[name="jumlah_pinjaman"]::-webkit-outer-spin-button,
        input[name="jumlah_pinjaman"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        #amount-display {
            font-variant-numeric: tabular-nums;
        }
    </style>
@endpush

@push('scripts')
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Format input jumlah pinjaman
                const amountInput = document.querySelector('input[name="jumlah_pinjaman"]');
                const amountDisplay = document.getElementById('amount-display');
                const tenorButtons = document.querySelectorAll('.tenor-button');
                const kategoriInputs = document.querySelectorAll('[name="kategori_pinjaman"]');
                const form = document.querySelector('form[action="{{ route('pinjaman.store') }}"]');
                const tenorHiddenInput = document.querySelector('input[name="tenor"]');

                // Set form ID
                if (form) {
                    form.id = 'pinjaman-form';
                }

                // Format angka ke Rupiah
                function formatRupiah(angka) {
                    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                // Function untuk update tenor availability berdasarkan jumlah pinjaman
                function updateTenorAvailability() {
                    const amount = amountInput ? parseInt(amountInput.value.replace(/\D/g, '')) || 0 : 0;
                    const currentTenor = parseInt(tenorHiddenInput.value);
                    let isCurrentTenorValid = true;

                    tenorButtons.forEach(button => {
                        const tenor = parseInt(button.dataset.tenor);

                        // Jika jumlah < 10 juta, hanya boleh pilih 3, 6, 9, 12
                        if (amount > 0 && amount < 10000000) {
                            if (tenor > 12) {
                                // Disable tenor > 12
                                button.disabled = true;
                                button.classList.add('tw-opacity-40', 'tw-cursor-not-allowed');
                                button.classList.remove('hover:tw-border-primary', 'hover:tw-bg-primary/5');

                                // Jika tenor yang aktif adalah yang disabled, tandai sebagai invalid
                                if (currentTenor === tenor) {
                                    isCurrentTenorValid = false;
                                }
                            } else {
                                // Enable tenor <= 12
                                button.disabled = false;
                                button.classList.remove('tw-opacity-40', 'tw-cursor-not-allowed');
                                button.classList.add('hover:tw-border-primary', 'hover:tw-bg-primary/5');
                            }
                        } else if (amount >= 10000000) {
                            // Jika >= 10 juta, semua tenor boleh
                            button.disabled = false;
                            button.classList.remove('tw-opacity-40', 'tw-cursor-not-allowed');
                            button.classList.add('hover:tw-border-primary', 'hover:tw-bg-primary/5');
                        }
                    });

                    // Jika tenor yang dipilih tidak valid, reset ke 12 bulan
                    if (!isCurrentTenorValid && amount < 10000000) {
                        tenorHiddenInput.value = 12;

                        // Update visual button
                        tenorButtons.forEach(btn => {
                            btn.classList.remove('tw-border-primary', 'tw-bg-primary/5');
                            btn.classList.add('tw-border-[#dbe6df]');

                            if (parseInt(btn.dataset.tenor) === 12) {
                                btn.classList.remove('tw-border-[#dbe6df]');
                                btn.classList.add('tw-border-primary', 'tw-bg-primary/5');
                            }
                        });

                        document.getElementById('tenor-display').textContent = '12 Bulan';
                        calculateAngsuran();
                    }
                }

                // Format input saat diisi
                if (amountInput) {
                    amountInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value) {
                            value = parseInt(value);
                            e.target.value = value;
                            amountDisplay.textContent = formatRupiah(value);

                            // Update tenor availability
                            updateTenorAvailability();

                            calculateAngsuran();
                        } else {
                            amountDisplay.textContent = 'Rp 0';
                        }
                    });

                    // Set initial value
                    if (amountInput.value) {
                        amountDisplay.textContent = formatRupiah(amountInput.value);
                        updateTenorAvailability();
                    }
                }

                // Handle tenor button selection
                tenorButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Jika button disabled, tidak bisa diklik
                        if (this.disabled) {
                            return;
                        }

                        // Remove active class from all buttons
                        tenorButtons.forEach(btn => {
                            btn.classList.remove('tw-border-primary', 'tw-bg-primary/5');
                            btn.classList.add('tw-border-[#dbe6df]');
                        });

                        // Add active class to clicked button
                        this.classList.remove('tw-border-[#dbe6df]');
                        this.classList.add('tw-border-primary', 'tw-bg-primary/5');

                        // Update hidden input
                        tenorHiddenInput.value = this.dataset.tenor;

                        // Update display
                        document.getElementById('tenor-display').textContent = this.dataset.tenor + ' Bulan';

                        calculateAngsuran();
                    });
                });

                // Handle kategori selection
                kategoriInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        calculateAngsuran();
                    });
                });

                // Calculate angsuran function
                function calculateAngsuran() {
                    const amount = amountInput ? parseInt(amountInput.value.replace(/\D/g, '')) || 0 : 0;
                    const tenor = tenorHiddenInput.value || 12;
                    const bunga = {{ $bunga_per_tahun ?? 1.5 }};

                    if (amount >= 500000 && tenor) {
                        fetch('{{ route("pinjaman.calculate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                jumlah_pinjaman: amount,
                                tenor: parseInt(tenor),
                                bunga_per_tahun: bunga
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Update summary
                            document.getElementById('pokok-pinjaman').textContent = formatRupiah(amount);
                            document.getElementById('total-bunga').textContent = formatRupiah(Math.round(data.total_bunga));
                            document.getElementById('angsuran-per-bulan').textContent = data.angsuran_formatted;
                            document.getElementById('total-bayar').textContent = data.total_bayar_formatted;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                }

                // Submit button handler
                const submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.addEventListener('click', function(e) {
                        const limitTersedia = {{ $limit_tersedia ?? 0 }};
                        if (limitTersedia <= 0) {
                            e.preventDefault();
                            alert('Limit pinjaman Anda telah penuh. Tidak dapat mengajukan pinjaman baru.');
                            return false;
                        }

                        // Validasi tenor berdasarkan jumlah pinjaman
                        const amount = parseInt(amountInput.value.replace(/\D/g, '')) || 0;
                        const tenor = parseInt(tenorHiddenInput.value);

                        if (amount < 10000000 && tenor > 12) {
                            e.preventDefault();
                            alert('Untuk pinjaman di bawah Rp 10.000.000, tenor maksimal adalah 12 bulan.');
                            return false;
                        }

                        // Validasi checkbox terms (jika ada)
                        const termsCheckbox = document.getElementById('terms');
                        if (termsCheckbox && !termsCheckbox.checked) {
                            e.preventDefault();
                            alert('Anda harus menyetujui syarat dan ketentuan terlebih dahulu.');
                            return false;
                        }
                    });
                }

                // Initial calculation and tenor setup
                updateTenorAvailability();
                calculateAngsuran();
            });
    </script>
@endpush

@section('content')
    <div class="tw-w-full tw-max-w-[1400px] tw-mx-auto tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6 lg:tw-gap-8 tw-my-4 tw-px-4">
        <div
            class="tw-col-span-1 lg:tw-col-span-12 tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-items-start md:tw-items-end tw-border-b tw-border-gray-200 dark:tw-border-[#2a4032] tw-pb-6 tw-mb-2 tw-gap-4 md:tw-gap-0">
            <div class="tw-flex tw-flex-col tw-gap-2">
                <h1
                    class="tw-text-[#111814] dark:tw-text-white tw-text-3xl lg:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-[-0.033em]">
                    Pengajuan Pinjaman Baru</h1>
                <p class="tw-text-[#618971] dark:tw-text-[#8abfa0] tw-text-base lg:tw-text-lg tw-font-medium">Koperasi Simpan Pinjam Sejahtera</p>
            </div>
            <div
                class="tw-flex tw-items-center tw-gap-3 tw-bg-white dark:tw-bg-[#1a2e22] tw-px-5 tw-py-3 tw-rounded-xl tw-border tw-border-[#e5e7eb] dark:tw-border-[#2a4032] tw-shadow-sm tw-w-full md:tw-w-auto">
                <div
                    class="tw-h-8 tw-w-8 tw-rounded-full tw-bg-primary/20 tw-flex tw-items-center tw-justify-center tw-text-primary-dark tw-shrink-0">
                    <span
                        class="material-symbols-outlined tw-text-lg tw-text-[#0d5226] dark:tw-text-primary">person</span>
                </div>
                <div class="tw-flex tw-flex-col">
                    <span class="tw-text-xs tw-text-[#618971] dark:tw-text-[#8abfa0] tw-font-bold tw-uppercase">Anggota
                        Aktif</span>
                    <span class="tw-text-sm tw-font-bold tw-text-[#111814] dark:tw-text-white">{{ $anggota->nama ?? 'Anggota' }}</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="tw-col-span-1 lg:tw-col-span-12">
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
            </div>
        @endif

        <form action="{{ route('pinjaman.store') }}" method="POST" class="tw-col-span-1 lg:tw-col-span-8 tw-flex tw-flex-col tw-gap-6 lg:tw-gap-8">
            @csrf
            <div
                class="tw-bg-white dark:tw-bg-[#1a2e22] tw-rounded-2xl tw-shadow-sm tw-border tw-border-[#e5e7eb] dark:tw-border-[#2a4032] tw-overflow-hidden">
                <div
                    class="tw-p-6 md:tw-p-8 tw-border-b tw-border-[#f0f2f5] dark:tw-border-[#2a4032] tw-bg-[#fcfdfd] dark:tw-bg-[#1a2e22] tw-flex tw-flex-col sm:tw-flex-row tw-items-start sm:tw-items-center tw-justify-between tw-gap-6 sm:tw-gap-0">
                    <div class="tw-flex tw-flex-col tw-gap-2">
                        <div class="tw-flex tw-items-center tw-gap-2 tw-text-[#618971] dark:tw-text-[#8abfa0]">
                            <span class="material-symbols-outlined tw-text-2xl">account_balance_wallet</span>
                            <span class="tw-text-sm tw-font-bold tw-uppercase tw-tracking-wide">Jumlah Pinjaman</span>
                        </div>
                        <div class="tw-flex tw-items-baseline tw-gap-3 tw-flex-wrap">
                            <h2
                                id="amount-display"
                                class="tw-text-[#111814] dark:tw-text-white tw-text-3xl lg:tw-text-4xl tw-font-bold tw-leading-tight">
                                Rp {{ number_format(old('jumlah_pinjaman', 5000000), 0, ',', '.') }}</h2>
                            <span
                                class="tw-px-2.5 tw-py-1 tw-rounded tw-text-xs tw-font-bold tw-bg-primary/20 tw-text-[#0d5226] dark:tw-text-primary tw-tracking-wide">ACTIVE</span>
                        </div>
                        <p class="tw-text-sm tw-text-[#618971] dark:tw-text-[#8abfa0]">
                            @if(($limit_tersedia ?? 0) > 0)
                                Limit tersedia: Rp {{ number_format($limit_tersedia, 0, ',', '.') }} dari maksimal Rp {{ number_format($limit_maksimal, 0, ',', '.') }}
                            @else
                                Limit pinjaman Anda telah penuh (Rp {{ number_format($limit_maksimal, 0, ',', '.') }})
                            @endif
                        </p>
                    </div>
                    <div
                        class="tw-h-16 tw-w-16 tw-rounded-full tw-bg-primary/10 tw-flex tw-items-center tw-justify-center tw-text-primary tw-shadow-inner tw-self-end sm:tw-self-center">
                        <span class="material-symbols-outlined tw-text-3xl">trending_up</span>
                    </div>
                </div>
                <div class="tw-p-6 md:tw-p-8 tw-flex tw-flex-col tw-gap-8 md:tw-gap-10">
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <span
                            class="tw-text-[#111814] dark:tw-text-white tw-text-lg tw-font-bold tw-flex tw-items-center tw-gap-2">
                            <span
                                class="tw-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-[#111814] tw-text-white tw-text-xs">1</span>
                            Pilih Kategori Pinjaman
                        </span>
                        <div class="tw-grid tw-grid-cols-1 sm:tw-grid-cols-2 tw-gap-4 md:tw-gap-6">
                            @foreach($kategori_options as $value => $label)
                            <label class="tw-cursor-pointer tw-group tw-relative">
                                <input
                                    type="radio"
                                    name="kategori_pinjaman"
                                    value="{{ $value }}"
                                    class="tw-peer tw-sr-only"
                                    {{ old('kategori_pinjaman', 'pinjaman_cash') == $value ? 'checked' : '' }}
                                    required />
                                <div
                                    class="tw-flex tw-items-center tw-gap-4 md:tw-gap-5 tw-p-4 md:tw-p-5 tw-rounded-xl tw-border-2 tw-border-[#dbe6df] dark:tw-border-[#4a6356] tw-bg-white dark:tw-bg-[#14261d] tw-transition-all hover:tw-border-primary peer-checked:tw-border-primary peer-checked:tw-bg-primary/5 tw-h-full tw-shadow-sm hover:tw-shadow-md">
                                    <div
                                        class="tw-flex-shrink-0 tw-h-12 tw-w-12 md:tw-h-14 md:tw-w-14 tw-rounded-full tw-bg-primary/10 dark:tw-bg-primary/20 tw-text-primary tw-flex tw-items-center tw-justify-center group-hover:tw-bg-primary/20 tw-transition-colors">
                                        <span
                                            class="material-symbols-outlined tw-text-2xl md:tw-text-3xl">
                                            {{ $value == 'pinjaman_cash' ? 'payments' : 'devices' }}
                                        </span>
                                    </div>
                                    <div class="tw-flex tw-flex-col">
                                        <span
                                            class="tw-text-[#111814] dark:tw-text-white tw-font-bold tw-text-base md:tw-text-lg">{{ $label }}</span>
                                        <span
                                            class="tw-text-[#618971] dark:tw-text-[#8abfa0] tw-text-xs md:tw-text-sm tw-leading-relaxed">
                                            {{ $value == 'pinjaman_cash' ? 'Dana tunai cepat cair ke rekening pribadi.' : 'Pembiayaan khusus pembelian gadget/elektronik.' }}
                                        </span>
                                    </div>
                                    <div
                                        class="tw-absolute tw-top-4 tw-right-4 tw-opacity-0 peer-checked:tw-opacity-100 tw-text-primary tw-transition-opacity">
                                        <span
                                            class="material-symbols-outlined tw-text-xl md:tw-text-2xl">check_circle</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('kategori_pinjaman')
                            <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <span
                            class="tw-text-[#111814] dark:tw-text-white tw-text-lg tw-font-bold tw-flex tw-items-center tw-gap-2">
                            <span
                                class="tw-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-[#111814] tw-text-white tw-text-xs">2</span>
                            Nominal Pinjaman
                        </span>
                        <div class="tw-w-full tw-relative tw-group">
                            <span
                                class="tw-absolute tw-left-6 tw-top-7 -tw-translate-y-1/2 tw-text-[#618971] tw-font-bold tw-text-xl md:tw-text-2xl group-focus-within:tw-text-primary tw-transition-colors">Rp</span>
                            <input
                                name="jumlah_pinjaman"
                                id="jumlah_pinjaman"
                                class="tw-form-input tw-flex tw-w-full tw-rounded-xl tw-text-[#111814] dark:tw-text-white focus:tw-outline-0 focus:tw-ring-2 focus:tw-ring-primary/50 tw-border-2 tw-border-[#dbe6df] dark:tw-border-[#4a6356] tw-bg-white dark:tw-bg-[#14261d] tw-h-14 md:tw-h-16 placeholder:tw-text-[#a0b3a8] tw-pl-14 md:tw-pl-16 tw-pr-6 tw-text-xl md:tw-text-2xl tw-font-bold tw-leading-normal tw-transition-all"
                                placeholder="0"
                                type="number"
                                min="500000"
                                max="{{ $limit_tersedia > 0 ? $limit_tersedia : $limit_maksimal }}"
                                step="100000"
                                value="{{ old('jumlah_pinjaman', 5000000) }}"
                                required />
                            <div
                                class="tw-flex tw-flex-col xs:tw-flex-row tw-justify-between tw-mt-2 tw-px-1 tw-gap-1 xs:tw-gap-0">
                                <span
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-[#618971] dark:tw-text-[#8abfa0]">Minimal
                                    Rp {{ number_format(500000, 0, ',', '.') }}</span>
                                <span
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-[#618971] dark:tw-text-[#8abfa0]">Maksimal
                                    Rp {{ number_format($limit_tersedia > 0 ? $limit_tersedia : $limit_maksimal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @error('jumlah_pinjaman')
                            <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                        @enderror

                        @if($limit_tersedia < $limit_maksimal && $limit_tersedia > 0)
                        <div class="tw-bg-yellow-50 dark:tw-bg-yellow-900/20 tw-rounded-lg tw-p-4 tw-border tw-border-yellow-200 dark:tw-border-yellow-800">
                            <div class="tw-flex tw-items-start tw-gap-3">
                                <span class="material-symbols-outlined tw-text-yellow-600 dark:tw-text-yellow-400">info</span>
                                <div class="tw-flex tw-flex-col tw-gap-1">
                                    <p class="tw-text-sm tw-font-medium tw-text-yellow-800 dark:tw-text-yellow-300">
                                        Anda memiliki sisa pinjaman aktif
                                    </p>
                                    <p class="tw-text-xs tw-text-yellow-700 dark:tw-text-yellow-500">
                                        Limit tersedia: Rp {{ number_format($limit_tersedia, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-4">
                        <div class="tw-flex tw-flex-wrap tw-items-center tw-justify-between tw-gap-2">
                            <span
                                class="tw-text-[#111814] dark:tw-text-white tw-text-lg tw-font-bold tw-flex tw-items-center tw-gap-2">
                                <span
                                    class="tw-flex tw-items-center tw-justify-center tw-w-6 tw-h-6 tw-rounded-full tw-bg-[#111814] tw-text-white tw-text-xs">3</span>
                                Pilih Tenor Pinjaman
                            </span>
                            <span
                                class="tw-text-sm tw-text-primary tw-font-bold tw-flex tw-items-center tw-gap-1">
                                <span class="material-symbols-outlined tw-text-sm">info</span>
                                Bunga: {{ $bunga_per_tahun }}% per tahun
                            </span>
                        </div>
                        <div class="tw-grid tw-grid-cols-2 sm:tw-grid-cols-4 tw-gap-3 md:tw-gap-4">
                            @foreach($tenor_options as $tenor)
                            <button
                                type="button"
                                data-tenor="{{ $tenor }}"
                                class="tenor-button tw-group tw-relative tw-flex tw-flex-col tw-items-center tw-justify-center tw-gap-1 tw-rounded-xl tw-border-2 {{ old('tenor', 12) == $tenor ? 'tw-border-primary tw-bg-primary/5' : 'tw-border-[#dbe6df] dark:tw-border-[#4a6356]' }} tw-bg-white dark:tw-bg-[#14261d] tw-py-5 md:tw-py-6 hover:tw-border-primary hover:tw-bg-primary/5 tw-transition-all focus:tw-ring-2 focus:tw-ring-primary focus:tw-outline-none">
                                <span
                                    class="tw-text-[#111814] dark:tw-text-white tw-text-lg md:tw-text-xl tw-font-bold">{{ $tenor }}</span>
                                <span
                                    class="tw-text-[#618971] tw-text-[10px] md:tw-text-xs tw-font-medium tw-uppercase tw-tracking-wider">Bulan</span>
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="tenor" value="{{ old('tenor', 12) }}">
                        <input type="hidden" name="bunga_per_tahun" value="{{ $bunga_per_tahun }}">
                        @error('tenor')
                            <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                        @enderror

                        <div class="tw-flex tw-flex-col tw-gap-2">
                            <label class="tw-text-[#111814] dark:tw-text-white tw-text-sm tw-font-bold tw-flex tw-items-center tw-gap-2">
                                <span class="material-symbols-outlined tw-text-base">notes</span>
                                Keterangan (Opsional)
                            </label>
                            <textarea
                                name="keterangan"
                                class="tw-form-textarea tw-w-full tw-rounded-xl tw-border tw-border-[#dbe6df] dark:tw-border-[#4a6356] tw-bg-white dark:tw-bg-[#14261d] focus:tw-ring-2 focus:tw-ring-primary/50 focus:tw-border-primary tw-p-4 tw-text-sm"
                                rows="3"
                                placeholder="Contoh: Untuk biaya pendidikan anak..."
                                maxlength="1000">{{ old('keterangan') }}</textarea>
                            <p class="tw-text-xs tw-text-[#618971] dark:tw-text-[#8abfa0]">
                                Berikan alasan atau keterangan penggunaan pinjaman (maksimal 1000 karakter)
                            </p>
                        </div>
                        @error('keterangan')
                            <p class="tw-text-red-500 tw-text-sm tw-mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </form>

        <div class="tw-col-span-1 lg:tw-col-span-4 tw-flex tw-flex-col tw-gap-6">
            <div
                class="tw-bg-white dark:tw-bg-[#1a2e22] tw-rounded-2xl tw-shadow-lg tw-border tw-border-[#e5e7eb] dark:tw-border-[#2a4032] tw-p-6 md:tw-p-8 tw-sticky tw-top-8">
                <h3
                    class="tw-text-lg md:tw-text-xl tw-font-bold tw-text-[#111814] dark:tw-text-white tw-mb-6 tw-flex tw-items-center tw-gap-2">
                    <span class="material-symbols-outlined tw-text-primary">receipt_long</span>
                    Ringkasan Pengajuan
                </h3>
                <div class="tw-space-y-4 tw-mb-8">
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-pb-3 tw-border-b tw-border-[#f0f2f5] dark:tw-border-[#2a4032]">
                        <span class="tw-text-sm tw-text-[#618971] dark:tw-text-[#8abfa0]">Pokok Pinjaman</span>
                        <span id="pokok-pinjaman" class="tw-text-base tw-font-semibold tw-text-[#111814] dark:tw-text-white">Rp {{ number_format(old('jumlah_pinjaman', 5000000), 0, ',', '.') }}</span>
                    </div>
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-pb-3 tw-border-b tw-border-[#f0f2f5] dark:tw-border-[#2a4032]">
                        <span class="tw-text-sm tw-text-[#618971] dark:tw-text-[#8abfa0]">Tenor</span>
                        <span id="tenor-display" class="tw-text-base tw-font-semibold tw-text-[#111814] dark:tw-text-white">{{ old('tenor', 12) }} Bulan</span>
                    </div>
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-pb-3 tw-border-b tw-border-[#f0f2f5] dark:tw-border-[#2a4032]">
                        <span class="tw-text-sm tw-text-[#618971] dark:tw-text-[#8abfa0]">Bunga ({{ $bunga_per_tahun }}%)</span>
                        <span id="total-bunga" class="tw-text-base tw-font-semibold tw-text-[#111814] dark:tw-text-white">Rp 0</span>
                    </div>
                    <div
                        class="tw-flex tw-items-center tw-justify-between tw-pb-3 tw-border-b tw-border-[#f0f2f5] dark:tw-border-[#2a4032]">
                        <span class="tw-text-sm tw-text-[#618971] dark:tw-text-[#8abfa0]">Total Pinjaman</span>
                        <span id="total-bayar" class="tw-text-base tw-font-semibold tw-text-[#111814] dark:tw-text-white">Rp 0</span>
                    </div>
                    <div class="tw-flex tw-flex-col tw-gap-1 tw-pt-2">
                        <span class="tw-text-sm tw-font-bold tw-text-[#111814] dark:tw-text-white">Estimasi
                            Angsuran</span>
                        <div class="tw-flex tw-items-baseline tw-gap-2">
                            <span
                                id="angsuran-per-bulan"
                                class="tw-text-2xl md:tw-text-3xl tw-font-black tw-text-primary dark:tw-text-primary">Rp 0</span>
                            <span class="tw-text-sm tw-font-medium tw-text-[#618971] dark:tw-text-[#8abfa0]">/
                                bulan</span>
                        </div>
                    </div>
                </div>
                <button
                    type="submit"
                    form="pinjaman-form"
                    class="tw-flex tw-w-full tw-items-center tw-justify-center tw-gap-3 tw-rounded-xl tw-bg-primary tw-h-12 md:tw-h-14 hover:tw-bg-[#25d366] active:tw-scale-[0.99] tw-transition-all tw-duration-200 tw-shadow-xl tw-shadow-primary/25 tw-group {{ $limit_tersedia <= 0 ? 'tw-opacity-50 tw-cursor-not-allowed' : '' }}"
                    {{ $limit_tersedia <= 0 ? 'disabled' : '' }}>
                    <span class="tw-text-[#102217] tw-text-base md:tw-text-lg tw-font-bold tw-tracking-tight">
                        {{ $limit_tersedia > 0 ? 'Ajukan Sekarang' : 'Limit Penuh' }}
                    </span>
                    <span
                        class="material-symbols-outlined tw-text-[#102217] tw-text-xl md:tw-text-2xl group-hover:tw-translate-x-1 tw-transition-transform">arrow_forward</span>
                </button>

                @if($limit_tersedia <= 0)
                <div class="tw-mt-4 tw-bg-red-50 dark:tw-bg-red-900/20 tw-rounded-lg tw-p-4 tw-border tw-border-red-200 dark:tw-border-red-800">
                    <div class="tw-flex tw-items-center tw-gap-3">
                        <span class="material-symbols-outlined tw-text-red-500">warning</span>
                        <p class="tw-text-sm tw-text-red-800 dark:tw-text-red-300">
                            Limit pinjaman Anda telah mencapai maksimum.
                            <a href="{{ route('pinjaman.index') }}" class="tw-font-bold tw-underline">Lihat pinjaman aktif</a>.
                        </p>
                    </div>
                </div>
                @endif
            </div>
            <p class="tw-text-center tw-text-sm tw-text-[#9ca3af] dark:tw-text-[#4a6356]">
                © {{ date('Y') }} Koperasi Simpan Pinjam Sejahtera.<br />Terdaftar dan diawasi.
            </p>
        </div>
    </div>
@endsection
