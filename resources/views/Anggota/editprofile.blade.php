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

    <style type="text/tailwindcss">
        @layer utilities {
            .form-input-custom {
                @apply tw-w-full tw-rounded-lg tw-border-border-light dark:tw-border-border-dark tw-bg-slate-50 dark:tw-bg-slate-800/50 tw-text-text-primary-light dark:tw-text-text-primary-dark tw-text-sm tw-px-3 tw-py-2.5 focus:tw-border-primary focus:tw-ring-1 focus:tw-ring-primary focus:tw-bg-white dark:focus:tw-bg-surface-dark tw-transition-colors;
            }

            .form-label-custom {
                @apply tw-block tw-text-xs tw-font-bold tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-mb-1.5 tw-uppercase tw-tracking-wide;
            }
        }
    </style>
@endpush
@section('content')
    <main class="tw-flex-1 tw-w-full tw-max-w-[1200px] tw-mx-auto tw-p-4 md:tw-p-8">
        <form action="{{ route('profile.update') }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')

            {{-- Alert Messages --}}
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

            <div
                class="tw-mb-8 tw-pb-6 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-flex-col md:tw-flex-row md:tw-items-end tw-justify-between tw-gap-6">
                <div>
                    <div class="tw-flex tw-items-center tw-gap-2 tw-mb-2 tw-text-primary tw-font-semibold tw-text-sm">
                        <span class="material-symbols-outlined tw-text-[18px]">arrow_back</span>
                        <a class="hover:tw-underline tw-no-underline" href="{{ route('profile.show') }}">Kembali ke Profil</a>
                    </div>
                    <h1
                        class="tw-text-text-primary-light dark:tw-text-text-primary-dark tw-text-3xl md:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-tight">
                        Edit Data Anggota
                    </h1>
                    <p
                        class="tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-text-base md:tw-text-lg tw-mt-2">
                        Silakan ubah data pada kolom formulir di bawah ini
                    </p>
                </div>
                <div class="tw-hidden md:tw-block">
                    <span
                        class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-rounded-full tw-bg-blue-50 dark:tw-bg-blue-900/30 tw-text-primary tw-text-xs tw-font-bold tw-border tw-border-blue-100 dark:tw-border-blue-800">
                        <span class="material-symbols-outlined tw-text-[16px]">edit_note</span>
                        MODE EDIT
                    </span>
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6">
                <div class="lg:tw-col-span-4 xl:tw-col-span-3 tw-flex tw-flex-col tw-gap-6">
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-p-6 tw-flex tw-flex-col tw-items-center tw-sticky tw-top-6">
                        <div class="tw-relative tw-mb-6 tw-group tw-cursor-pointer">
                            <label class="tw-cursor-pointer tw-block tw-relative" for="foto-upload">
                                <div class="tw-h-32 tw-w-32 tw-rounded-full tw-bg-cover tw-bg-center tw-border-4 tw-border-primary/20 group-hover:tw-border-primary tw-transition-colors"
                                    id="foto-preview"
                                    style="background-image: url('{{ $foto_url }}');">
                                </div>
                                <div
                                    class="tw-absolute tw-inset-0 tw-bg-black/40 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity">
                                    <span class="material-symbols-outlined tw-text-white tw-text-3xl">photo_camera</span>
                                </div>
                                <div
                                    class="tw-absolute tw-bottom-0 tw-right-0 tw-bg-primary tw-text-white tw-p-2 tw-rounded-full tw-shadow-md tw-border-2 tw-border-white dark:tw-border-surface-dark tw-z-10 tw-transition-transform group-hover:tw-scale-110">
                                    <span class="material-symbols-outlined tw-text-[18px] tw-block">edit</span>
                                </div>
                            </label>
                            <input accept="image/*" class="tw-hidden" id="foto-upload" name="foto" type="file" />
                            <p class="tw-text-center tw-text-xs tw-text-text-secondary-light tw-mt-2">Klik untuk ubah foto
                            </p>
                        </div>

                        <div class="tw-w-full tw-h-px tw-bg-border-light dark:tw-bg-border-dark tw-mb-6"></div>
                        <div class="tw-w-full tw-flex tw-flex-col tw-gap-5 tw-text-left">
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">No. Anggota</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">badge</span>
                                    <input class="form-input-custom tw-font-mono tw-pl-10 tw-bg-slate-100 dark:tw-bg-slate-900/50"
                                        name="no_anggota" type="text" value="{{ old('no_anggota', $anggota->no_anggota) }}" readonly />
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">No. Registrasi</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">confirmation_number</span>
                                    <input class="form-input-custom tw-font-mono tw-pl-10 tw-bg-slate-100 dark:tw-bg-slate-900/50"
                                        name="no_registrasi" type="text" value="{{ $anggota->no_registrasi }}" readonly />
                                </div>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">Grup Wilayah</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">groups</span>
                                    <select class="form-input-custom tw-pl-10" name="grup_wilayah">
                                        @foreach($grup_wilayah_options as $option)
                                            <option value="{{ $option }}" {{ old('grup_wilayah', $anggota->grup_wilayah) == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
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
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-3 tw-bg-slate-50 dark:tw-bg-slate-800/30">
                            <div
                                class="tw-p-2 tw-rounded-lg tw-bg-white dark:tw-bg-surface-dark tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark">
                                <span class="material-symbols-outlined tw-text-primary tw-block">card_membership</span>
                            </div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Akun &amp; Keanggotaan</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-6">
                            <div>
                                <label class="form-label-custom">Tanggal Daftar</label>
                                <input class="form-input-custom tw-bg-slate-100 dark:tw-bg-slate-900/50"
                                    name="tanggal_daftar" type="date"
                                    value="{{ old('tanggal_daftar', $anggota->tanggal_daftar?->format('Y-m-d')) }}" readonly />
                            </div>
                            <div>
                                <label class="form-label-custom">Keterangan</label>
                                <input class="form-input-custom" name="keterangan" placeholder="Tambahkan catatan..."
                                    type="text" value="{{ old('keterangan', $anggota->keterangan) }}" />
                            </div>
                        </div>
                    </div>

                    {{-- Identitas Diri --}}
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-3 tw-bg-slate-50 dark:tw-bg-slate-800/30">
                            <div
                                class="tw-p-2 tw-rounded-lg tw-bg-white dark:tw-bg-surface-dark tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark">
                                <span class="material-symbols-outlined tw-text-primary tw-block">person</span>
                            </div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Identitas Diri</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-6">
                            <div class="md:tw-col-span-2">
                                <label class="form-label-custom">Nama Lengkap <span class="tw-text-red-500">*</span></label>
                                <input class="form-input-custom tw-text-base tw-font-semibold" name="nama" type="text"
                                    value="{{ old('nama', $anggota->nama) }}" required />
                            </div>
                            <div>
                                <label class="form-label-custom">Tempat Lahir</label>
                                <input class="form-input-custom" name="tempat_lahir" type="text"
                                    value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}" />
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Lahir</label>
                                <input class="form-input-custom" name="tanggal_lahir" type="date"
                                    value="{{ old('tanggal_lahir', $anggota->tanggal_lahir?->format('Y-m-d')) }}" />
                            </div>
                            <div>
                                <label class="form-label-custom">Jenis Kelamin</label>
                                <select class="form-input-custom" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach($jenis_kelamin_options as $jk)
                                        <option value="{{ $jk }}" {{ old('jenis_kelamin', $anggota->jenis_kelamin) == $jk ? 'selected' : '' }}>
                                            {{ $jk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Agama</label>
                                <select class="form-input-custom" name="agama">
                                    <option value="">-- Pilih Agama --</option>
                                    @foreach($agama_options as $agama)
                                        <option value="{{ $agama }}" {{ old('agama', $anggota->agama) == $agama ? 'selected' : '' }}>
                                            {{ $agama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Nama Pasangan</label>
                                <input class="form-input-custom" name="nama_pasangan" type="text"
                                    value="{{ old('nama_pasangan', $anggota->nama_pasangan) }}" />
                            </div>
                        </div>
                    </div>

                    {{-- Dokumen & Kontak --}}
                    <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-6">
                        <div
                            class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden tw-flex tw-flex-col tw-h-full">
                            <div
                                class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-3 tw-bg-slate-50 dark:tw-bg-slate-800/30">
                                <div
                                    class="tw-p-2 tw-rounded-lg tw-bg-white dark:tw-bg-surface-dark tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark">
                                    <span class="material-symbols-outlined tw-text-primary tw-block">description</span>
                                </div>
                                <h3
                                    class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    Dokumen Identitas</h3>
                            </div>
                            <div class="tw-p-6 tw-flex tw-flex-col tw-gap-6 tw-flex-1">
                                <div>
                                    <label class="form-label-custom">Jenis Identitas</label>
                                    <select class="form-input-custom" name="jenis_identitas">
                                        <option value="">-- Pilih --</option>
                                        @foreach($jenis_identitas_options as $jenis)
                                            <option value="{{ $jenis }}" {{ old('jenis_identitas', $anggota->jenis_identitas) == $jenis ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Nomor Identitas</label>
                                    <input class="form-input-custom tw-font-mono tw-tracking-wide" name="no_identitas"
                                        type="text" value="{{ old('no_identitas', $anggota->no_identitas) }}" />
                                </div>
                                <div>
                                    <label class="form-label-custom">Berlaku Sampai</label>
                                    <input class="form-input-custom" name="berlaku_sampai" type="text"
                                        value="{{ old('berlaku_sampai', $anggota->berlaku_sampai?->format('Y-m-d') ?? 'Seumur Hidup') }}" />
                                </div>
                            </div>
                        </div>

                        <div
                            class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden tw-flex tw-flex-col tw-h-full">
                            <div
                                class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-3 tw-bg-slate-50 dark:tw-bg-slate-800/30">
                                <div
                                    class="tw-p-2 tw-rounded-lg tw-bg-white dark:tw-bg-surface-dark tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark">
                                    <span class="material-symbols-outlined tw-text-primary tw-block">contact_phone</span>
                                </div>
                                <h3
                                    class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                    Kontak &amp; Alamat</h3>
                            </div>
                            <div class="tw-p-6 tw-flex tw-flex-col tw-gap-6 tw-flex-1">
                                <div>
                                    <label class="form-label-custom">Nomor Telepon</label>
                                    <div class="tw-relative">
                                        <span
                                            class="material-symbols-outlined tw-text-sm tw-text-text-secondary-light tw-absolute tw-left-3 tw-top-3">call</span>
                                        <input class="form-input-custom tw-pl-9" name="no_telepon" type="tel"
                                            value="{{ old('no_telepon', $anggota->no_telepon) }}" />
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label-custom">Alamat Rumah</label>
                                    <textarea class="form-input-custom tw-resize-none tw-h-32"
                                        name="alamat">{{ old('alamat', $anggota->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pekerjaan --}}
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-overflow-hidden">
                        <div
                            class="tw-px-6 tw-py-4 tw-border-b tw-border-border-light dark:tw-border-border-dark tw-flex tw-items-center tw-gap-3 tw-bg-slate-50 dark:tw-bg-slate-800/30">
                            <div
                                class="tw-p-2 tw-rounded-lg tw-bg-white dark:tw-bg-surface-dark tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark">
                                <span class="material-symbols-outlined tw-text-primary tw-block">work</span>
                            </div>
                            <h3 class="tw-text-lg tw-font-bold tw-text-text-primary-light dark:tw-text-text-primary-dark">
                                Pekerjaan &amp; Keuangan</h3>
                        </div>
                        <div class="tw-p-6 tw-grid tw-grid-cols-1 md:tw-grid-cols-2 tw-gap-y-6 tw-gap-x-6">
                            <div>
                                <label class="form-label-custom">Pekerjaan</label>
                                <input class="form-input-custom" name="pekerjaan" type="text"
                                    value="{{ old('pekerjaan', $anggota->pekerjaan) }}" />
                            </div>
                            <div>
                                <label class="form-label-custom">Pendapatan Per Bulan</label>
                                <div class="tw-relative">
                                    <span
                                        class="tw-absolute tw-left-3 tw-top-2.5 tw-text-text-secondary-light tw-text-sm tw-font-semibold">Rp</span>
                                    <input class="form-input-custom tw-pl-10" name="pendapatan" type="number"
                                        value="{{ old('pendapatan', $anggota->pendapatan) }}" />
                                </div>
                            </div>
                            <div class="md:tw-col-span-2">
                                <label class="form-label-custom">Alamat Kantor</label>
                                <textarea class="form-input-custom tw-resize-none" name="alamat_kantor"
                                    rows="2">{{ old('alamat_kantor', $anggota->alamat_kantor) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div
                        class="tw-mt-4 tw-pt-6 tw-border-t tw-border-border-light dark:tw-border-border-dark tw-flex tw-flex-col-reverse md:tw-flex-row tw-justify-end tw-items-center tw-gap-4">
                        <a href="{{ route('profile.show') }}"
                            class="tw-w-full md:tw-w-auto tw-px-6 tw-py-3 tw-rounded-lg tw-border tw-border-border-light dark:tw-border-border-dark tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-font-bold hover:tw-bg-slate-100 dark:hover:tw-bg-slate-800 tw-transition-colors tw-text-center tw-no-underline">
                            Batal
                        </a>
                        <button
                            class="tw-w-full md:tw-w-auto tw-px-8 tw-py-3 tw-rounded-lg tw-bg-primary tw-text-white tw-font-bold tw-shadow-lg tw-shadow-primary/30 hover:tw-bg-blue-600 active:tw-scale-95 tw-transition-all tw-flex tw-items-center tw-justify-center tw-gap-2"
                            type="submit">
                            <span class="material-symbols-outlined">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    @push('scripts')
    <script>
        // Preview foto sebelum upload
        document.getElementById('foto-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('foto-preview').style.backgroundImage = `url('${e.target.result}')`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
@endsection
