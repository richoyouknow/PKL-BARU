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
        <form action="{{ route('profile.store') }}" enctype="multipart/form-data" method="POST">
            @csrf

            {{-- Alert Messages --}}
            @if(session('info'))
                <div class="tw-bg-blue-50 dark:tw-bg-blue-900/30 tw-border tw-border-blue-200 dark:tw-border-blue-800 tw-text-blue-800 dark:tw-text-blue-200 tw-px-4 tw-py-3 tw-rounded-lg tw-mb-6">
                    <div class="tw-flex tw-items-start tw-gap-2">
                        <span class="material-symbols-outlined tw-mt-0.5">info</span>
                        <p>{{ session('info') }}</p>
                    </div>
                </div>
            @endif

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
                        <span class="material-symbols-outlined tw-text-[18px]">person_add</span>
                        <span>Pendaftaran Anggota Baru</span>
                    </div>
                    <h1
                        class="tw-text-text-primary-light dark:tw-text-text-primary-dark tw-text-3xl md:tw-text-4xl tw-font-black tw-leading-tight tw-tracking-tight">
                        Lengkapi Data Anggota
                    </h1>
                    <p
                        class="tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-text-base md:tw-text-lg tw-mt-2">
                        Silakan isi formulir di bawah ini untuk mendaftar sebagai anggota
                    </p>
                </div>
                <div class="tw-hidden md:tw-block">
                    <span
                        class="tw-inline-flex tw-items-center tw-gap-1.5 tw-px-3 tw-py-1.5 tw-rounded-full tw-bg-green-50 dark:tw-bg-green-900/30 tw-text-green-600 dark:tw-text-green-400 tw-text-xs tw-font-bold tw-border tw-border-green-100 dark:tw-border-green-800">
                        <span class="material-symbols-outlined tw-text-[16px]">add_circle</span>
                        PENDAFTARAN BARU
                    </span>
                </div>
            </div>

            <div class="tw-grid tw-grid-cols-1 lg:tw-grid-cols-12 tw-gap-6">
                <div class="lg:tw-col-span-4 xl:tw-col-span-3 tw-flex tw-flex-col tw-gap-6">
                    <div
                        class="tw-bg-surface-light dark:tw-bg-surface-dark tw-rounded-xl tw-shadow-sm tw-border tw-border-border-light dark:tw-border-border-dark tw-p-6 tw-flex tw-flex-col tw-items-center tw-sticky tw-top-6">
                        <div class="tw-relative tw-mb-6 tw-group tw-cursor-pointer">
                            <label class="tw-cursor-pointer tw-block tw-relative" for="foto-upload">
                                <div class="tw-h-32 tw-w-32 tw-rounded-full tw-bg-cover tw-bg-center tw-border-4 tw-border-primary/20 group-hover:tw-border-primary tw-transition-colors tw-bg-slate-200 dark:tw-bg-slate-700"
                                    id="foto-preview"
                                    style="background-image: url('{{ asset('images/default-avatar.png') }}');">
                                </div>
                                <div
                                    class="tw-absolute tw-inset-0 tw-bg-black/40 tw-rounded-full tw-flex tw-items-center tw-justify-center tw-opacity-0 group-hover:tw-opacity-100 tw-transition-opacity">
                                    <span class="material-symbols-outlined tw-text-white tw-text-3xl">photo_camera</span>
                                </div>
                                <div
                                    class="tw-absolute tw-bottom-0 tw-right-0 tw-bg-primary tw-text-white tw-p-2 tw-rounded-full tw-shadow-md tw-border-2 tw-border-white dark:tw-border-surface-dark tw-z-10 tw-transition-transform group-hover:tw-scale-110">
                                    <span class="material-symbols-outlined tw-text-[18px] tw-block">add_photo_alternate</span>
                                </div>
                            </label>
                            <input accept="image/*" class="tw-hidden" id="foto-upload" name="foto" type="file" />
                            <p class="tw-text-center tw-text-xs tw-text-text-secondary-light tw-mt-2">Upload foto profil</p>
                        </div>

                        <div class="tw-w-full tw-h-px tw-bg-border-light dark:tw-bg-border-dark tw-mb-6"></div>
                        <div class="tw-w-full tw-flex tw-flex-col tw-gap-5 tw-text-left">
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">No. Anggota</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">badge</span>
                                    <input class="form-input-custom tw-font-mono tw-pl-10 tw-bg-slate-100 dark:tw-bg-slate-900/50"
                                        placeholder="Otomatis dibuat sistem" type="text" readonly />
                                </div>
                                <p class="tw-text-xs tw-text-text-secondary-light tw-mt-1">Format: AGT-YYYY-XXXX</p>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">No. Registrasi</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">confirmation_number</span>
                                    <input class="form-input-custom tw-font-mono tw-pl-10 tw-bg-slate-100 dark:tw-bg-slate-900/50"
                                        placeholder="Otomatis dibuat sistem" type="text" readonly />
                                </div>
                                <p class="tw-text-xs tw-text-text-secondary-light tw-mt-1">Format: REG-YYYYMMDD-XXXX</p>
                            </div>
                            <div class="tw-flex tw-flex-col tw-gap-1">
                                <label class="form-label-custom">Grup Wilayah</label>
                                <div class="tw-relative">
                                    <span
                                        class="material-symbols-outlined tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-absolute tw-left-3 tw-top-2.5 tw-text-[20px]">groups</span>
                                    <select class="form-input-custom tw-pl-10" name="grup_wilayah">
                                        <option value="Calon Anggota" selected>Calon Anggota</option>
                                        @foreach($grup_wilayah_options as $option)
                                            <option value="{{ $option }}" {{ old('grup_wilayah') == $option ? 'selected' : '' }}>
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
                                    value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required />
                            </div>
                            <div>
                                <label class="form-label-custom">Tempat Lahir</label>
                                <input class="form-input-custom" name="tempat_lahir" type="text"
                                    value="{{ old('tempat_lahir') }}" placeholder="Kota kelahiran" />
                            </div>
                            <div>
                                <label class="form-label-custom">Tanggal Lahir</label>
                                <input class="form-input-custom" name="tanggal_lahir" type="date"
                                    value="{{ old('tanggal_lahir') }}" />
                            </div>
                            <div>
                                <label class="form-label-custom">Jenis Kelamin</label>
                                <select class="form-input-custom" name="jenis_kelamin">
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    @foreach($jenis_kelamin_options as $jk)
                                        <option value="{{ $jk }}" {{ old('jenis_kelamin') == $jk ? 'selected' : '' }}>
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
                                        <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>
                                            {{ $agama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Nama Pasangan</label>
                                <input class="form-input-custom" name="nama_pasangan" type="text"
                                    value="{{ old('nama_pasangan') }}" placeholder="Opsional" />
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
                                            <option value="{{ $jenis }}" {{ old('jenis_identitas') == $jenis ? 'selected' : '' }}>
                                                {{ $jenis }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Nomor Identitas</label>
                                    <input class="form-input-custom tw-font-mono tw-tracking-wide" name="no_identitas"
                                        type="text" value="{{ old('no_identitas') }}" placeholder="Nomor KTP/SIM/Paspor" />
                                </div>
                                <div>
                                    <label class="form-label-custom">Berlaku Sampai</label>
                                    <input class="form-input-custom" name="berlaku_sampai" type="text"
                                        value="{{ old('berlaku_sampai') }}" placeholder="Seumur Hidup / YYYY-MM-DD" />
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
                                            value="{{ old('no_telepon') }}" placeholder="08xx xxxx xxxx" />
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label-custom">Alamat Rumah</label>
                                    <textarea class="form-input-custom tw-resize-none tw-h-32"
                                        name="alamat" placeholder="Alamat lengkap tempat tinggal">{{ old('alamat') }}</textarea>
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
                                    value="{{ old('pekerjaan') }}" placeholder="Jabatan / profesi" />
                            </div>
                            <div>
                                <label class="form-label-custom">Pendapatan Per Bulan</label>
                                <div class="tw-relative">
                                    <span
                                        class="tw-absolute tw-left-3 tw-top-2.5 tw-text-text-secondary-light tw-text-sm tw-font-semibold">Rp</span>
                                    <input class="form-input-custom tw-pl-10" name="pendapatan" type="number"
                                        value="{{ old('pendapatan') }}" placeholder="0" />
                                </div>
                            </div>
                            <div class="md:tw-col-span-2">
                                <label class="form-label-custom">Alamat Kantor</label>
                                <textarea class="form-input-custom tw-resize-none" name="alamat_kantor"
                                    rows="2" placeholder="Alamat tempat bekerja (opsional)">{{ old('alamat_kantor') }}</textarea>
                            </div>
                            <div class="md:tw-col-span-2">
                                <label class="form-label-custom">Keterangan Tambahan</label>
                                <textarea class="form-input-custom tw-resize-none" name="keterangan"
                                    rows="2" placeholder="Catatan atau informasi tambahan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div
                        class="tw-mt-4 tw-pt-6 tw-border-t tw-border-border-light dark:tw-border-border-dark tw-flex tw-flex-col-reverse md:tw-flex-row tw-justify-end tw-items-center tw-gap-4">
                        <a href="{{ route('beranda') }}"
                            class="tw-w-full md:tw-w-auto tw-px-6 tw-py-3 tw-rounded-lg tw-border tw-border-border-light dark:tw-border-border-dark tw-text-text-secondary-light dark:tw-text-text-secondary-dark tw-font-bold hover:tw-bg-slate-100 dark:hover:tw-bg-slate-800 tw-transition-colors tw-text-center tw-no-underline">
                            Batal
                        </a>
                        <button
                            class="tw-w-full md:tw-w-auto tw-px-8 tw-py-3 tw-rounded-lg tw-bg-primary tw-text-white tw-font-bold tw-shadow-lg tw-shadow-primary/30 hover:tw-bg-blue-600 active:tw-scale-95 tw-transition-all tw-flex tw-items-center tw-justify-center tw-gap-2"
                            type="submit">
                            <span class="material-symbols-outlined">person_add</span>
                            Daftar Sebagai Anggota
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
