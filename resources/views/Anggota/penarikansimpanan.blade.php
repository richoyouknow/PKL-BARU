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
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
@endpush

@section('content')
    <div class="tw-relative tw-flex tw-h-full tw-w-full tw-flex-col group/design-root tw-overflow-x-hidden">
        <div class="tw-layout-container tw-flex tw-h-full tw-grow tw-flex-col">
            <div class="tw-mx-auto tw-w-full tw-max-w-3xl tw-px-4 sm:tw-px-6 lg:tw-px-8 tw-py-6 md:tw-py-8">

                {{-- Back Button --}}
                <a href="{{ route('simpanan.dashboard') }}"
                   class="tw-inline-flex tw-items-center tw-gap-2 tw-text-slate-600 dark:tw-text-slate-400 hover:tw-text-slate-900 dark:hover:tw-text-white tw-mb-6 tw-no-underline">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="tw-font-medium">Kembali ke Dashboard</span>
                </a>

                {{-- Header --}}
                <div class="tw-mb-6">
                    <h1 class="tw-text-2xl md:tw-text-3xl tw-font-black tw-text-slate-900 dark:tw-text-white tw-mb-2">
                        Ajukan Penarikan Simpanan
                    </h1>
                    <p class="tw-text-slate-600 dark:tw-text-slate-400">
                        Isi form di bawah untuk mengajukan penarikan simpanan. Pengajuan akan diproses oleh admin.
                    </p>
                </div>

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="tw-bg-red-50 dark:tw-bg-red-900/30 tw-border tw-border-red-200 dark:tw-border-red-800 tw-text-red-800 dark:tw-text-red-200 tw-px-4 tw-py-3 tw-rounded-lg tw-mb-6">
                        <div class="tw-flex tw-items-start tw-gap-2">
                            <span class="material-symbols-outlined tw-mt-0.5">error</span>
                            <div>
                                <p class="tw-font-semibold tw-mb-1">Terjadi kesalahan:</p>
                                <ul class="tw-list-disc tw-list-inside tw-space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <div class="tw-bg-white dark:tw-bg-slate-800/50 tw-rounded-xl tw-shadow-sm tw-border tw-border-slate-200 dark:tw-border-slate-700 tw-p-6">

                    @if($simpananList->count() > 0)
                        <form action="{{ route('simpanan.penarikan.submit') }}" method="POST">
                            @csrf

                            {{-- Pilih Simpanan --}}
                            <div class="tw-mb-6">
                                <label for="simpanan_id" class="tw-block tw-text-sm tw-font-bold tw-text-slate-900 dark:tw-text-white tw-mb-2">
                                    Pilih Rekening Simpanan
                                    <span class="tw-text-red-500">*</span>
                                </label>
                                <select
                                    name="simpanan_id"
                                    id="simpanan_id"
                                    required
                                    class="tw-w-full tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-slate-300 dark:tw-border-slate-600 tw-bg-white dark:tw-bg-slate-700 tw-text-slate-900 dark:tw-text-white focus:tw-ring-2 focus:tw-ring-primary focus:tw-border-transparent">
                                    <option value="">-- Pilih Rekening --</option>
                                    @foreach($simpananList as $simpanan)
                                        <option value="{{ $simpanan->id }}" data-saldo="{{ $simpanan->saldo }}">
                                            {{ $simpanan->no_rekening }} - {{ $simpanan->jenis_simpanan_formatted }}
                                            (Saldo: Rp {{ number_format($simpanan->saldo, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah Penarikan --}}
                            <div class="tw-mb-6">
                                <label for="jumlah" class="tw-block tw-text-sm tw-font-bold tw-text-slate-900 dark:tw-text-white tw-mb-2">
                                    Jumlah Penarikan
                                    <span class="tw-text-red-500">*</span>
                                </label>
                                <div class="tw-relative">
                                    <span class="tw-absolute tw-left-4 tw-top-3.5 tw-text-slate-500 dark:tw-text-slate-400 tw-font-medium">
                                        Rp
                                    </span>
                                    <input
                                        type="number"
                                        name="jumlah"
                                        id="jumlah"
                                        min="10000"
                                        step="1000"
                                        required
                                        placeholder="0"
                                        class="tw-w-full tw-pl-12 tw-pr-4 tw-py-3 tw-rounded-lg tw-border tw-border-slate-300 dark:tw-border-slate-600 tw-bg-white dark:tw-bg-slate-700 tw-text-slate-900 dark:tw-text-white focus:tw-ring-2 focus:tw-ring-primary focus:tw-border-transparent">
                                </div>
                                <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-mt-1">
                                    Minimal penarikan Rp 10.000
                                </p>
                                <p id="saldo-info" class="tw-text-sm tw-text-slate-600 dark:tw-text-slate-400 tw-mt-2 tw-hidden">
                                    Saldo tersedia: <span class="tw-font-bold" id="saldo-amount"></span>
                                </p>
                            </div>

                            {{-- Keterangan --}}
                            <div class="tw-mb-6">
                                <label for="keterangan" class="tw-block tw-text-sm tw-font-bold tw-text-slate-900 dark:tw-text-white tw-mb-2">
                                    Keterangan/Keperluan
                                    <span class="tw-text-red-500">*</span>
                                </label>
                                <textarea
                                    name="keterangan"
                                    id="keterangan"
                                    rows="4"
                                    required
                                    placeholder="Contoh: Untuk biaya pendidikan anak"
                                    class="tw-w-full tw-px-4 tw-py-3 tw-rounded-lg tw-border tw-border-slate-300 dark:tw-border-slate-600 tw-bg-white dark:tw-bg-slate-700 tw-text-slate-900 dark:tw-text-white focus:tw-ring-2 focus:tw-ring-primary focus:tw-border-transparent resize-none"></textarea>
                                <p class="tw-text-xs tw-text-slate-500 dark:tw-text-slate-400 tw-mt-1">
                                    Jelaskan keperluan penarikan simpanan Anda
                                </p>
                            </div>

                            {{-- Info Box --}}
                            <div class="tw-bg-blue-50 dark:tw-bg-blue-900/30 tw-border tw-border-blue-200 dark:tw-border-blue-800 tw-rounded-lg tw-p-4 tw-mb-6">
                                <div class="tw-flex tw-items-start tw-gap-3">
                                    <span class="material-symbols-outlined tw-text-blue-600 dark:tw-text-blue-400 tw-mt-0.5">
                                        info
                                    </span>
                                    <div class="tw-text-sm tw-text-blue-800 dark:tw-text-blue-200">
                                        <p class="tw-font-semibold tw-mb-1">Informasi Penting:</p>
                                        <ul class="tw-list-disc tw-list-inside tw-space-y-1">
                                            <li>Pengajuan penarikan akan diverifikasi oleh admin</li>
                                            <li>Proses verifikasi memakan waktu 1-3 hari kerja</li>
                                            <li>Anda akan mendapat notifikasi setelah pengajuan disetujui/ditolak</li>
                                            <li>Pastikan data yang Anda isi sudah benar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="tw-flex tw-gap-3">
                                <button
                                    type="submit"
                                    class="tw-flex-1 tw-flex tw-items-center tw-justify-center tw-gap-2 tw-px-6 tw-py-3 tw-bg-primary hover:tw-bg-green-400 tw-text-slate-900 tw-font-bold tw-rounded-lg tw-transition-all tw-shadow-md hover:tw-shadow-lg">
                                    <span class="material-symbols-outlined">send</span>
                                    Ajukan Penarikan
                                </button>
                                <a
                                    href="{{ route('simpanan.dashboard') }}"
                                    class="tw-px-6 tw-py-3 tw-border tw-border-slate-300 dark:tw-border-slate-600 tw-text-slate-700 dark:tw-text-slate-300 tw-font-bold tw-rounded-lg hover:tw-bg-slate-100 dark:hover:tw-bg-slate-700 tw-transition-all tw-no-underline tw-text-center">
                                    Batal
                                </a>
                            </div>
                        </form>
                    @else
                        {{-- No Simpanan Available --}}
                        <div class="tw-text-center tw-py-12">
                            <span class="material-symbols-outlined tw-text-6xl tw-text-slate-300 dark:tw-text-slate-600 tw-mb-4">
                                account_balance_wallet
                            </span>
                            <h3 class="tw-text-xl tw-font-bold tw-text-slate-900 dark:tw-text-white tw-mb-2">
                                Tidak Ada Simpanan Aktif
                            </h3>
                            <p class="tw-text-slate-600 dark:tw-text-slate-400 tw-mb-6">
                                Anda belum memiliki simpanan aktif atau saldo simpanan Anda kosong.
                            </p>
                            <a
                                href="{{ route('simpanan.dashboard') }}"
                                class="tw-inline-flex tw-items-center tw-gap-2 tw-px-6 tw-py-3 tw-bg-primary hover:tw-bg-green-400 tw-text-slate-900 tw-font-bold tw-rounded-lg tw-transition-all tw-no-underline">
                                <span class="material-symbols-outlined">arrow_back</span>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const simpananSelect = document.getElementById('simpanan_id');
            const jumlahInput = document.getElementById('jumlah');
            const saldoInfo = document.getElementById('saldo-info');
            const saldoAmount = document.getElementById('saldo-amount');

            simpananSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const saldo = parseFloat(selectedOption.dataset.saldo || 0);

                if (saldo > 0) {
                    saldoAmount.textContent = 'Rp ' + saldo.toLocaleString('id-ID');
                    saldoInfo.classList.remove('tw-hidden');
                    jumlahInput.max = saldo;
                } else {
                    saldoInfo.classList.add('tw-hidden');
                    jumlahInput.removeAttribute('max');
                }
            });

            // Validasi jumlah penarikan
            jumlahInput.addEventListener('input', function() {
                const selectedOption = simpananSelect.options[simpananSelect.selectedIndex];
                const saldo = parseFloat(selectedOption.dataset.saldo || 0);
                const jumlah = parseFloat(this.value || 0);

                if (jumlah > saldo) {
                    this.setCustomValidity('Jumlah penarikan melebihi saldo yang tersedia');
                } else if (jumlah < 10000) {
                    this.setCustomValidity('Minimal penarikan Rp 10.000');
                } else {
                    this.setCustomValidity('');
                }
            });
        });
    </script>
    @endpush
@endsection
