<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PinjamanResource\Pages;
use App\Models\Pinjaman;
use App\Models\Anggota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class PinjamanResource extends Resource
{
    protected static ?string $model = Pinjaman::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pinjaman';

    protected static ?string $pluralModelLabel = 'Pinjaman';

    protected static ?string $modelLabel = 'Pinjaman';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Anggota')
                    ->schema([
                        Forms\Components\Select::make('anggota_id')
                            ->label('Anggota')
                            ->relationship('anggota', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Detail Pinjaman')
                    ->schema([
                        Forms\Components\TextInput::make('no_pinjaman')
                            ->label('No. Pinjaman')
                            ->default(fn () => Pinjaman::generateNoPinjaman())
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('kategori_pinjaman')
                            ->label('Kategori Pinjaman')
                            ->options([
                                'pinjaman_cash' => 'Pinjaman Cash',
                                'pinjaman_elektronik' => 'Pinjaman Elektronik',
                            ])
                            ->default('pinjaman_cash')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                // Reset fields when kategori changes
                                if ($state === 'pinjaman_elektronik') {
                                    $set('jumlah_pinjaman', 0);
                                    $set('total_pinjaman_dengan_bunga', 0);
                                    $set('saldo_pinjaman', 0);
                                    $set('angsuran_per_bulan', 0);
                                    $set('tanggal_pinjaman', null);
                                    $set('tanggal_jatuh_tempo', null);
                                }
                            })
                            ->native(false),

                        Forms\Components\TextInput::make('jumlah_pinjaman')
                            ->label('Jumlah Pinjaman (Pokok)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->disabled(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_elektronik')
                            ->dehydrated()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if ($get('kategori_pinjaman') === 'pinjaman_elektronik') {
                                    return; // Skip calculation for elektronik
                                }

                                $set('bunga_per_tahun', 1.5);
                                $set('tenor', null);
                                $set('total_pinjaman_dengan_bunga', null);
                                $set('saldo_pinjaman', null);
                                $set('angsuran_per_bulan', null);
                            })
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Nominal akan diisi setelah verifikasi barang di koperasi'
                                    : 'Pinjaman < Rp 10.000.000 hanya bisa tenor 3, 6, 9, 12 bulan'
                            ),

                        Forms\Components\Select::make('tenor')
                            ->label('Tenor (Bulan)')
                            ->options(function (Get $get) {
                                $kategori = $get('kategori_pinjaman');

                                // Untuk elektronik, semua tenor tersedia
                                if ($kategori === 'pinjaman_elektronik') {
                                    return [
                                        3 => '3 Bulan',
                                        6 => '6 Bulan',
                                        9 => '9 Bulan',
                                        12 => '12 Bulan',
                                        18 => '18 Bulan',
                                        24 => '24 Bulan',
                                        30 => '30 Bulan',
                                        36 => '36 Bulan',
                                    ];
                                }

                                // Untuk cash, tergantung jumlah
                                $jumlah = floatval($get('jumlah_pinjaman') ?? 0);

                                if ($jumlah > 0 && $jumlah < 10000000) {
                                    return [
                                        3 => '3 Bulan',
                                        6 => '6 Bulan',
                                        9 => '9 Bulan',
                                        12 => '12 Bulan',
                                    ];
                                }

                                return [
                                    3 => '3 Bulan',
                                    6 => '6 Bulan',
                                    9 => '9 Bulan',
                                    12 => '12 Bulan',
                                    18 => '18 Bulan',
                                    24 => '24 Bulan',
                                    30 => '30 Bulan',
                                    36 => '36 Bulan',
                                ];
                            })
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if (!$state) return;

                                $kategori = $get('kategori_pinjaman');

                                // Skip calculation untuk elektronik
                                if ($kategori === 'pinjaman_elektronik') {
                                    return;
                                }

                                $jumlah = $get('jumlah_pinjaman');
                                $bunga = 1.5;
                                $tanggal_pinjaman = $get('tanggal_pinjaman');

                                if ($jumlah) {
                                    // Hitung angsuran per bulan
                                    $angsuran = Pinjaman::hitungAngsuran(
                                        floatval($jumlah),
                                        intval($state),
                                        $bunga
                                    );
                                    $set('angsuran_per_bulan', round($angsuran, 2));

                                    // Hitung total pinjaman dengan bunga
                                    $totalDenganBunga = round($angsuran, 2) * intval($state);
                                    $set('total_pinjaman_dengan_bunga', round($totalDenganBunga, 2));

                                    // Set saldo pinjaman = total dengan bunga
                                    $set('saldo_pinjaman', round($totalDenganBunga, 2));
                                }

                                if ($tanggal_pinjaman) {
                                    try {
                                        $jatuhTempo = \Carbon\Carbon::parse($tanggal_pinjaman)
                                            ->addMonths(intval($state));
                                        $set('tanggal_jatuh_tempo', $jatuhTempo->format('Y-m-d'));
                                    } catch (\Exception $e) {
                                        // Handle error
                                    }
                                }
                            })
                            ->disabled(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash' && !$get('jumlah_pinjaman'))
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_cash'
                                    ? ($get('jumlah_pinjaman') ? 'Pilih tenor sesuai dengan jumlah pinjaman' : 'Isi jumlah pinjaman terlebih dahulu')
                                    : 'Pilih tenor yang diinginkan'
                            ),

                        Forms\Components\TextInput::make('bunga_per_tahun')
                            ->label('Bunga per Tahun (%)')
                            ->default(1.5)
                            ->numeric()
                            ->suffix('%')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->helperText('Bunga tetap 1,5% per tahun'),

                        Forms\Components\TextInput::make('angsuran_per_bulan')
                            ->label('Angsuran per Bulan')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Akan dihitung setelah nominal ditentukan'
                                    : 'Dihitung otomatis'
                            ),

                        Forms\Components\TextInput::make('total_pinjaman_dengan_bunga')
                            ->label('Total Pinjaman + Bunga')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Akan dihitung setelah nominal ditentukan'
                                    : 'Total yang harus dibayar (pokok + bunga)'
                            ),

                        Forms\Components\TextInput::make('saldo_pinjaman')
                            ->label('Saldo Pinjaman (Sisa)')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Akan diisi setelah nominal ditentukan'
                                    : 'Sisa yang harus dibayar'
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Tanggal')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_pinjaman')
                            ->label('Tanggal Pinjaman')
                            ->default(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash' ? now() : null)
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->disabled(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_elektronik')
                            ->dehydrated()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                if (!$state || $get('kategori_pinjaman') === 'pinjaman_elektronik') return;

                                $tenor = $get('tenor');

                                if ($tenor) {
                                    try {
                                        $jatuhTempo = \Carbon\Carbon::parse($state)
                                            ->addMonths(intval($tenor));
                                        $set('tanggal_jatuh_tempo', $jatuhTempo->format('Y-m-d'));
                                    } catch (\Exception $e) {
                                        // Handle error
                                    }
                                }
                            })
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Akan diisi saat pencairan'
                                    : null
                            ),

                        Forms\Components\DatePicker::make('tanggal_jatuh_tempo')
                            ->label('Tanggal Jatuh Tempo')
                            ->required(fn (Get $get) => $get('kategori_pinjaman') === 'pinjaman_cash')
                            ->disabled()
                            ->dehydrated()
                            ->helperText(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Akan dihitung saat pencairan'
                                    : null
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status & Keterangan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'diajukan' => 'Diajukan',
                                'diproses' => 'Diproses',
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
                                'aktif' => 'Aktif',
                                'lunas' => 'Lunas',
                                'macet' => 'Macet',
                            ])
                            ->default('diajukan')
                            ->required()
                            ->native(false),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder(fn (Get $get) =>
                                $get('kategori_pinjaman') === 'pinjaman_elektronik'
                                    ? 'Detail barang elektronik yang diminta akan ditampilkan di sini...'
                                    : 'Keterangan tambahan (opsional)'
                            ),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_pinjaman')
                    ->label('No. Pinjaman')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Nama Anggota')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori_pinjaman')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->kategori_pinjaman_label)
                    ->color(fn ($record) => $record->isPinjamanElektronik() ? 'info' : 'success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jumlah_pinjaman')
                    ->label('Pinjaman Pokok')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable()
                    ->description(fn (Pinjaman $record) =>
                        $record->isPinjamanElektronik() && $record->jumlah_pinjaman == 0
                            ? '⚠️ Belum ditentukan'
                            : null
                    ),

                Tables\Columns\TextColumn::make('total_pinjaman_dengan_bunga')
                    ->label('Total + Bunga')
                    ->money('IDR')
                    ->sortable()
                    ->description(fn (Pinjaman $record) =>
                        $record->total_bunga > 0
                            ? 'Bunga: Rp ' . number_format($record->total_bunga, 0, ',', '.')
                            : ($record->isPinjamanElektronik() ? 'Menunggu verifikasi' : null)
                    ),

                Tables\Columns\TextColumn::make('saldo_pinjaman')
                    ->label('Sisa Bayar')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn ($record) => $record->saldo_pinjaman > 0 ? 'warning' : 'success'),

                Tables\Columns\TextColumn::make('progress_tenor')
                    ->label('Progress Tenor')
                    ->state(function (Pinjaman $record) {
                        if ($record->angsuran_per_bulan == 0 || $record->tenor == 0) {
                            return "0/{$record->tenor} bulan";
                        }

                        $totalDibayar = $record->total_pinjaman_dengan_bunga - $record->saldo_pinjaman;
                        $angsuranTerbayar = (int) floor($totalDibayar / $record->angsuran_per_bulan);

                        return "{$angsuranTerbayar}/{$record->tenor} bulan";
                    })
                    ->badge()
                    ->color(function (Pinjaman $record) {
                        if ($record->tenor == 0 || $record->angsuran_per_bulan == 0) return 'gray';

                        $totalDibayar = $record->total_pinjaman_dengan_bunga - $record->saldo_pinjaman;
                        $angsuranTerbayar = (int) floor($totalDibayar / $record->angsuran_per_bulan);
                        $persen = ($angsuranTerbayar / $record->tenor) * 100;

                        return match (true) {
                            $persen >= 100 => 'success',
                            $persen >= 75 => 'info',
                            $persen >= 50 => 'warning',
                            default => 'danger',
                        };
                    })
                    ->description(function (Pinjaman $record) {
                        if ($record->tenor == 0 || $record->angsuran_per_bulan == 0) {
                            return $record->isPinjamanElektronik() && $record->jumlah_pinjaman == 0
                                ? 'Menunggu verifikasi'
                                : '0% selesai';
                        }

                        $totalDibayar = $record->total_pinjaman_dengan_bunga - $record->saldo_pinjaman;
                        $angsuranTerbayar = (int) floor($totalDibayar / $record->angsuran_per_bulan);
                        $persen = min(($angsuranTerbayar / $record->tenor) * 100, 100);

                        return number_format($persen, 1) . '% selesai';
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("CASE
                            WHEN angsuran_per_bulan = 0 THEN 0
                            ELSE FLOOR((total_pinjaman_dengan_bunga - saldo_pinjaman) / angsuran_per_bulan)
                        END {$direction}");
                    }),

                Tables\Columns\TextColumn::make('angsuran_per_bulan')
                    ->label('Angsuran/Bulan')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_pinjaman')
                    ->label('Tgl Pinjaman')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Belum ditentukan'),

                Tables\Columns\TextColumn::make('tanggal_jatuh_tempo')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->tanggal_jatuh_tempo && $record->tanggal_jatuh_tempo->isPast() && $record->saldo_pinjaman > 0 ? 'danger' : null)
                    ->toggleable()
                    ->placeholder('Belum ditentukan'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'diajukan' => 'warning',
                        'diproses' => 'info',
                        'disetujui' => 'success',
                        'ditolak' => 'danger',
                        'aktif' => 'primary',
                        'lunas' => 'success',
                        'macet' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'diajukan' => 'Diajukan',
                        'diproses' => 'Diproses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        'aktif' => 'Aktif',
                        'lunas' => 'Lunas',
                        'macet' => 'Macet',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('kategori_pinjaman')
                    ->label('Kategori')
                    ->options([
                        'pinjaman_cash' => 'Pinjaman Cash',
                        'pinjaman_elektronik' => 'Pinjaman Elektronik',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('tenor')
                    ->label('Tenor')
                    ->options([
                        3 => '3 Bulan',
                        6 => '6 Bulan',
                        9 => '9 Bulan',
                        12 => '12 Bulan',
                        18 => '18 Bulan',
                        24 => '24 Bulan',
                        30 => '30 Bulan',
                        36 => '36 Bulan',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('anggota')
                    ->relationship('anggota', 'nama')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),

                    // Action khusus untuk Update Nominal Elektronik
                    Tables\Actions\Action::make('update_nominal_elektronik')
                        ->label('Set Nominal')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('warning')
                        ->visible(fn (Pinjaman $record) =>
                            $record->isPinjamanElektronik()
                            && in_array($record->status, ['diajukan', 'disetujui'])
                            && $record->jumlah_pinjaman == 0
                        )
                        ->form([
                            Forms\Components\Placeholder::make('info')
                                ->label('Informasi Pinjaman')
                                ->content(fn (Pinjaman $record) =>
                                    "**No. Pinjaman:** {$record->no_pinjaman}\n\n" .
                                    "**Anggota:** {$record->anggota->nama}\n\n" .
                                    "**Tenor:** {$record->tenor} bulan\n\n" .
                                    "**Keterangan Barang:**\n\n" .
                                    ($record->keterangan ?? 'Tidak ada keterangan')
                                ),

                            Forms\Components\TextInput::make('jumlah_pinjaman')
                                ->label('Nominal Pinjaman (Pokok)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->minValue(500000)
                                ->helperText('Masukkan nominal pinjaman setelah verifikasi barang'),

                            Forms\Components\Textarea::make('catatan_admin')
                                ->label('Catatan Admin')
                                ->rows(3)
                                ->placeholder('Catatan mengenai verifikasi barang (opsional)'),
                        ])
                        ->action(function (Pinjaman $record, array $data) {
                            $jumlah = floatval($data['jumlah_pinjaman']);

                            if ($record->updateNominalElektronik($jumlah, auth()->id())) {
                                // Tambah catatan admin jika ada
                                if (!empty($data['catatan_admin'])) {
                                    $record->keterangan .= "\n\n[CATATAN ADMIN] " . $data['catatan_admin'];
                                    $record->save();
                                }

                                Notification::make()
                                    ->title('Nominal Berhasil Diset')
                                    ->success()
                                    ->body("Nominal pinjaman elektronik berhasil diset: Rp " . number_format($jumlah, 0, ',', '.'))
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Gagal Set Nominal')
                                    ->danger()
                                    ->body('Nominal tidak dapat diset. Periksa status pinjaman.')
                                    ->send();
                            }
                        }),

                    // Action untuk Verifikasi & Cairkan Pinjaman
                    Tables\Actions\Action::make('verifikasi_cairkan')
                        ->label('Verifikasi & Cairkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Pinjaman $record) =>
                            in_array($record->status, ['diajukan', 'disetujui'])
                            && ($record->isPinjamanCash() || ($record->isPinjamanElektronik() && $record->jumlah_pinjaman > 0))
                        )
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi dan Cairkan Pinjaman')
                        ->modalDescription(fn (Pinjaman $record) =>
                            "Apakah Anda yakin ingin mencairkan pinjaman {$record->no_pinjaman}?\n\n" .
                            "Kategori: {$record->kategori_pinjaman_label}\n" .
                            "Jumlah Pokok: Rp " . number_format($record->jumlah_pinjaman, 0, ',', '.') . "\n" .
                            "Total + Bunga: Rp " . number_format($record->total_pinjaman_dengan_bunga, 0, ',', '.') . "\n" .
                            "Total Bunga: Rp " . number_format($record->total_bunga, 0, ',', '.')
                        )
                        ->modalSubmitActionLabel('Ya, Cairkan')
                        ->action(function (Pinjaman $record) {
                            if ($record->cairkan(auth()->id())) {
                                Notification::make()
                                    ->title('Pinjaman Berhasil Dicairkan')
                                    ->success()
                                    ->body("Pinjaman {$record->no_pinjaman} telah dicairkan.")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Gagal Mencairkan Pinjaman')
                                    ->danger()
                                    ->body('Pinjaman tidak dapat dicairkan. Periksa status pinjaman.')
                                    ->send();
                            }
                        }),

                    // Action untuk Pembayaran Angsuran
                    Tables\Actions\Action::make('bayar_angsuran')
                        ->label('Bayar Angsuran')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('primary')
                        ->visible(fn (Pinjaman $record) => $record->status === 'aktif' && $record->saldo_pinjaman > 0)
                        ->form([
                            Forms\Components\Placeholder::make('info_pinjaman')
                                ->label('')
                                ->content(fn (Pinjaman $record) =>
                                    "**Kategori:** {$record->kategori_pinjaman_label}\n\n" .
                                    "**Pinjaman Pokok:** Rp " . number_format($record->jumlah_pinjaman, 0, ',', '.') . "\n\n" .
                                    "**Total + Bunga:** Rp " . number_format($record->total_pinjaman_dengan_bunga, 0, ',', '.') . "\n\n" .
                                    "**Total Bunga:** Rp " . number_format($record->total_bunga, 0, ',', '.') . "\n\n" .
                                    "**Saldo Pinjaman:** Rp " . number_format($record->saldo_pinjaman, 0, ',', '.') . "\n\n" .
                                    "**Angsuran per Bulan:** Rp " . number_format($record->angsuran_per_bulan, 0, ',', '.') . "\n\n" .
                                    "**Sisa Tenor:** {$record->sisa_tenor} bulan"
                                ),

                            Forms\Components\Select::make('jenis_pembayaran')
                                ->label('Jenis Pembayaran')
                                ->options(fn (Pinjaman $record) => [
                                    'angsuran' => 'Angsuran Normal (Rp ' . number_format($record->angsuran_per_bulan, 0, ',', '.') . ')',
                                    'pelunasan' => 'Bayar Langsung/Lunas (Rp ' . number_format($record->saldo_pinjaman, 0, ',', '.') . ')',
                                    'custom' => 'Jumlah Custom',
                                ])
                                ->default('angsuran')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set, Get $get, Pinjaman $record) {
                                    $jenis = $get('jenis_pembayaran');

                                    // Update jumlah berdasarkan jenis pembayaran
                                    match($jenis) {
                                        'angsuran' => $set('jumlah', $record->angsuran_per_bulan),
                                        'pelunasan' => $set('jumlah', $record->saldo_pinjaman),
                                        default => $set('jumlah', null),
                                    };
                                })
                                ->native(false),

                            Forms\Components\TextInput::make('jumlah')
                                ->label('Jumlah Pembayaran')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->disabled(fn (Get $get) => $get('jenis_pembayaran') !== 'custom')
                                ->dehydrated()
                                ->maxValue(fn (Pinjaman $record) => $record->saldo_pinjaman)
                                ->minValue(1)
                                ->helperText(fn (Pinjaman $record) => "Maksimal: Rp " . number_format($record->saldo_pinjaman, 0, ',', '.')),

                            Forms\Components\Textarea::make('keterangan')
                                ->label('Keterangan')
                                ->rows(3)
                                ->placeholder('Catatan pembayaran (opsional)'),
                        ])
                        ->action(function (Pinjaman $record, array $data) {
                            $jumlah = floatval($data['jumlah']);
                            $keterangan = $data['keterangan'] ?? "Pembayaran angsuran pinjaman {$record->no_pinjaman}";

                            if ($record->bayarAngsuran($jumlah, $keterangan, auth()->id())) {
                                Notification::make()
                                    ->title('Pembayaran Berhasil')
                                    ->success()
                                    ->body("Pembayaran sebesar Rp " . number_format($jumlah, 0, ',', '.') . " telah diproses.")
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Pembayaran Gagal')
                                    ->danger()
                                    ->body('Pembayaran tidak dapat diproses.')
                                    ->send();
                            }
                        }),

                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPinjamen::route('/'),
            'create' => Pages\CreatePinjaman::route('/create'),
            'view' => Pages\ViewPinjaman::route('/{record}'),
            'edit' => Pages\EditPinjaman::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'diajukan')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
