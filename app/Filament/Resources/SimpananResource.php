<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SimpananResource\Pages;
use App\Filament\Resources\SimpananResource\RelationManagers;
use App\Models\Simpanan;
use App\Models\Anggota;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class SimpananResource extends Resource
{
    protected static ?string $model = Simpanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Simpanan';

    protected static ?string $modelLabel = 'Simpanan';

    protected static ?string $pluralModelLabel = 'Daftar Simpanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Simpanan')
                    ->description('Isi data simpanan anggota')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('anggota_id')
                                    ->label('Anggota')
                                    ->relationship('anggota', 'nama')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $anggota = Anggota::find($state);
                                            if ($anggota) {
                                                $existingPokok = Simpanan::where('anggota_id', $state)
                                                    ->where('jenis_simpanan', Simpanan::JENIS_POKOK)
                                                    ->exists();

                                                if (!$existingPokok) {
                                                    $set('jenis_simpanan', Simpanan::JENIS_POKOK);
                                                }
                                            }
                                        }
                                    }),

                                Forms\Components\Select::make('jenis_simpanan')
                                    ->label('Jenis Simpanan')
                                    ->options(Simpanan::getJenisOptions())
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, $operation) {
                                        if ($state && $operation === 'create') {
                                            $set('no_simpanan', Simpanan::generateNoSimpanan($state));
                                        }
                                    }),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_simpanan')
                                    ->label('Nomor Simpanan')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(function ($operation, $get) {
                                        if ($operation === 'create' && $get('jenis_simpanan')) {
                                            return Simpanan::generateNoSimpanan($get('jenis_simpanan'));
                                        }
                                        return null;
                                    })
                                    ->helperText('Format: Simp-POK/WJB/SKL/BJK-YYYYMMDD-XXXX (Otomatis)'),

                                Forms\Components\Placeholder::make('info_rekening')
                                    ->label('Informasi Rekening Anggota')
                                    ->content(function ($get, $record) {
                                        $anggotaId = $get('anggota_id') ?? $record?->anggota_id;

                                        if (!$anggotaId) {
                                            return 'Pilih anggota terlebih dahulu';
                                        }

                                        $anggota = Anggota::find($anggotaId);

                                        if (!$anggota) {
                                            return 'Anggota tidak ditemukan';
                                        }

                                        if ($anggota->no_rekening && $anggota->nama_bank) {
                                            return "{$anggota->nama_bank} - {$anggota->no_rekening} a.n {$anggota->atas_nama}";
                                        }

                                        return 'Anggota belum memiliki rekening terdaftar';
                                    })
                                    ->hidden(fn ($operation, $get) => $operation === 'create' && !$get('anggota_id')),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('saldo')
                                    ->label('Saldo Awal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->default(0)
                                    ->minValue(0)
                                    ->step(500)
                                    ->helperText('Isi saldo awal simpanan'),

                                Forms\Components\Select::make('status')
                                    ->label('Status Simpanan')
                                    ->options(Simpanan::getStatusOptions())
                                    ->required()
                                    ->default(Simpanan::STATUS_AKTIF)
                                    ->visibleOn(['edit', 'create']),
                            ]),

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Dibuat pada')
                            ->content(fn ($record): ?string => $record?->created_at?->translatedFormat('d F Y H:i:s'))
                            ->hiddenOn('create'),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Terakhir diubah')
                            ->content(fn ($record): ?string => $record?->updated_at?->translatedFormat('d F Y H:i:s'))
                            ->hiddenOn('create'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_simpanan')
                    ->label('No. Simpanan')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor simpanan berhasil disalin')
                    ->weight('bold'),

                TextColumn::make('anggota.no_rekening')
                    ->label('No. Rekening')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Nomor rekening berhasil disalin')
                    ->placeholder('-'),

                TextColumn::make('anggota.nama_bank')
                    ->label('Bank')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                TextColumn::make('anggota.nama')
                    ->label('Nama Anggota')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->anggota->no_anggota ?? '-'),

                TextColumn::make('jenis_simpanan')
                    ->label('Jenis Simpanan')
                    ->formatStateUsing(fn ($state) => Simpanan::getJenisOptions()[$state] ?? $state)
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        Simpanan::JENIS_POKOK => 'primary',
                        Simpanan::JENIS_WAJIB => 'success',
                        Simpanan::JENIS_SUKARELA => 'warning',
                        Simpanan::JENIS_BERJANGKA => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('saldo')
                    ->label('Saldo')
                    ->money('IDR')
                    ->sortable()
                    ->alignEnd()
                    ->description(fn ($record) => $record->isDitutup() ? 'Rekening Ditutup' : null),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => Simpanan::getStatusOptions()[$state] ?? $state)
                    ->colors([
                        'success' => Simpanan::STATUS_AKTIF,
                        'warning' => Simpanan::STATUS_NONAKTIF,
                        'danger' => Simpanan::STATUS_DITUTUP,
                    ]),

                TextColumn::make('transaksi_penarikan_count')
                    ->label('Penarikan Pending')
                    ->counts([
                        'transaksi as transaksi_penarikan_count' => fn ($query) => $query
                            ->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                            ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI)
                    ])
                    ->badge()
                    ->color('warning')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state . ' Penarikan' : '-')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Buka')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('jenis_simpanan')
                    ->label('Jenis Simpanan')
                    ->options(Simpanan::getJenisOptions())
                    ->multiple(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Simpanan::getStatusOptions())
                    ->multiple(),

                SelectFilter::make('anggota_id')
                    ->label('Anggota')
                    ->relationship('anggota', 'nama')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('has_pending_penarikan')
                    ->label('Penarikan Pending')
                    ->placeholder('Semua')
                    ->trueLabel('Ada Penarikan Pending')
                    ->falseLabel('Tidak Ada Penarikan')
                    ->queries(
                        true: fn ($query) => $query->whereHas('transaksi', function ($q) {
                            $q->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                              ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI);
                        }),
                        false: fn ($query) => $query->whereDoesntHave('transaksi', function ($q) {
                            $q->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                              ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI);
                        }),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('verifikasi_penarikan')
                    ->label('Verifikasi Penarikan')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(function ($record) {
                        return $record->transaksi()
                            ->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                            ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI)
                            ->exists();
                    })
                    ->badge()
                    ->badgeColor('warning')
                    ->modalHeading('Verifikasi Penarikan Simpanan')
                    ->modalWidth('2xl')
                    ->form(function ($record) {
                        $transaksi = $record->transaksi()
                            ->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                            ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI)
                            ->with('anggota')
                            ->latest()
                            ->first();

                        if (!$transaksi) {
                            return [
                                Forms\Components\Placeholder::make('no_data')
                                    ->label('')
                                    ->content('Tidak ada penarikan yang menunggu verifikasi.')
                            ];
                        }

                        $saldoAkhir = $record->saldo - $transaksi->jumlah;
                        $saldoClass = $saldoAkhir < 0 ? 'text-red-600 font-bold' : 'text-green-600 font-bold';

                        return [
                            Forms\Components\Hidden::make('transaksi_id')
                                ->default($transaksi->id),

                            Section::make('Informasi Penarikan')
                                ->schema([
                                    Forms\Components\Placeholder::make('info_kode')
                                        ->label('Kode Transaksi')
                                        ->content($transaksi->kode_transaksi ?? '-'),

                                    Forms\Components\Placeholder::make('info_anggota')
                                        ->label('Nama Anggota')
                                        ->content($transaksi->anggota->nama ?? '-'),

                                    Forms\Components\Placeholder::make('info_simpanan')
                                        ->label('Jenis Simpanan')
                                        ->content(Simpanan::getJenisOptions()[$record->jenis_simpanan] ?? '-'),

                                    Forms\Components\Placeholder::make('info_saldo')
                                        ->label('Saldo Saat Ini')
                                        ->content('Rp ' . number_format($record->saldo, 0, ',', '.')),

                                    Forms\Components\Placeholder::make('info_jumlah')
                                        ->label('Jumlah Penarikan')
                                        ->content('Rp ' . number_format($transaksi->jumlah, 0, ',', '.'))
                                        ->extraAttributes([
                                            'class' => 'text-lg font-bold'
                                        ]),

                                    Forms\Components\Placeholder::make('info_saldo_akhir')
                                        ->label('Saldo Setelah Penarikan')
                                        ->content('Rp ' . number_format($saldoAkhir, 0, ',', '.'))
                                        ->extraAttributes([
                                            'class' => $saldoClass
                                        ]),

                                    Forms\Components\Placeholder::make('info_keterangan')
                                        ->label('Keterangan')
                                        ->content($transaksi->keterangan ?? '-'),

                                    Forms\Components\Placeholder::make('info_tanggal')
                                        ->label('Tanggal Pengajuan')
                                        ->content($transaksi->created_at->translatedFormat('d F Y H:i:s')),
                                ])
                                ->columns(2),

                            Section::make('Verifikasi')
                                ->schema([
                                    Forms\Components\Select::make('status_verifikasi')
                                        ->label('Keputusan')
                                        ->options([
                                            'disetujui' => 'Setujui Penarikan',
                                            'ditolak' => 'Tolak Penarikan',
                                        ])
                                        ->required()
                                        ->reactive()
                                        ->default('disetujui'),

                                    Forms\Components\Textarea::make('catatan_verifikasi')
                                        ->label('Catatan Verifikasi')
                                        ->placeholder('Isi catatan verifikasi (opsional)')
                                        ->rows(3)
                                        ->maxLength(500),
                                ])
                        ];
                    })
                    ->action(function ($record, array $data) {
                        if (!isset($data['transaksi_id'])) {
                            Notification::make()
                                ->title('Error')
                                ->body('ID Transaksi tidak ditemukan. Silakan refresh halaman.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $transaksi = Transaksi::find($data['transaksi_id']);

                        if (!$transaksi) {
                            Notification::make()
                                ->title('Transaksi tidak ditemukan')
                                ->body('Transaksi mungkin sudah diproses atau dihapus.')
                                ->danger()
                                ->send();
                            return;
                        }

                        if ($transaksi->status !== Transaksi::STATUS_MENUNGGU_VERIFIKASI) {
                            Notification::make()
                                ->title('Transaksi sudah diproses')
                                ->body('Transaksi ini sudah diverifikasi sebelumnya.')
                                ->warning()
                                ->send();
                            return;
                        }

                        DB::beginTransaction();
                        try {
                            if ($data['status_verifikasi'] === 'disetujui') {
                                // Validasi saldo
                                if ($record->saldo < $transaksi->jumlah) {
                                    throw new \Exception('Saldo tidak mencukupi untuk penarikan ini!');
                                }

                                $saldoSebelum = $record->saldo;
                                $saldoSesudah = $saldoSebelum - $transaksi->jumlah;

                                // Update saldo simpanan
                                $record->update([
                                    'saldo' => $saldoSesudah
                                ]);

                                // Update transaksi
                                $transaksi->update([
                                    'status' => Transaksi::STATUS_SUKSES,
                                    'saldo_sebelum' => $saldoSebelum,
                                    'saldo_sesudah' => $saldoSesudah,
                                    'admin_id' => auth()->id(),
                                    'diverifikasi_pada' => now(),
                                    'keterangan' => $transaksi->keterangan . ' | Verifikasi: ' . ($data['catatan_verifikasi'] ?? 'Disetujui'),
                                ]);

                                DB::commit();

                                Notification::make()
                                    ->title('Penarikan Disetujui')
                                    ->body('Penarikan sebesar Rp ' . number_format($transaksi->jumlah, 0, ',', '.') . ' telah disetujui.')
                                    ->success()
                                    ->send();
                            } else {
                                // Update transaksi menjadi gagal
                                $transaksi->update([
                                    'status' => Transaksi::STATUS_GAGAL,
                                    'admin_id' => auth()->id(),
                                    'diverifikasi_pada' => now(),
                                    'keterangan' => $transaksi->keterangan . ' | Verifikasi: ' . ($data['catatan_verifikasi'] ?? 'Ditolak'),
                                ]);

                                DB::commit();

                                Notification::make()
                                    ->title('Penarikan Ditolak')
                                    ->body('Penarikan telah ditolak.')
                                    ->warning()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            DB::rollBack();

                            Notification::make()
                                ->title('Terjadi Kesalahan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record, $action) {
                        if ($record->saldo > 0) {
                            Notification::make()
                                ->title('Gagal Menghapus')
                                ->body('Simpanan tidak dapat dihapus karena masih memiliki saldo.')
                                ->danger()
                                ->send();

                            $action->halt();
                        }
                    }),

                Tables\Actions\Action::make('tutup')
                    ->label('Tutup')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tutup Rekening Simpanan')
                    ->modalDescription('Apakah Anda yakin ingin menutup rekening ini?')
                    ->visible(fn ($record) => $record->isAktif())
                    ->action(function ($record) {
                        $record->update(['status' => Simpanan::STATUS_DITUTUP]);
                        return redirect()->to(SimpananResource::getUrl('index'));
                    })
                    ->successNotificationTitle('Rekening berhasil ditutup'),

                Tables\Actions\Action::make('aktifkan')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Kembali Rekening')
                    ->modalDescription('Apakah Anda yakin ingin mengaktifkan kembali rekening ini?')
                    ->visible(fn ($record) => $record->isDitutup() || $record->isNonaktif())
                    ->action(function ($record) {
                        $record->update(['status' => Simpanan::STATUS_AKTIF]);
                        return redirect()->to(SimpananResource::getUrl('index'));
                    })
                    ->successNotificationTitle('Rekening berhasil diaktifkan'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->saldo > 0) {
                                    throw new \Exception('Tidak dapat menghapus simpanan yang masih memiliki saldo!');
                                }
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         RelationManagers\TransaksiRelationManager::class,
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSimpanans::route('/'),
            'create' => Pages\CreateSimpanan::route('/create'),
            'view' => Pages\ViewSimpanan::route('/{record}'),
            'edit' => Pages\EditSimpanan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['anggota'])
            ->withCount([
                'transaksi as transaksi_penarikan_count' => fn ($query) => $query
                    ->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                    ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI)
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereHas('transaksi', function($query) {
            $query->where('jenis_transaksi', Transaksi::JENIS_PENARIKAN_SIMPANAN)
                  ->where('status', Transaksi::STATUS_MENUNGGU_VERIFIKASI);
        })->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
