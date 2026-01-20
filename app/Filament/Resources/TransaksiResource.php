<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transaksi';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\TextInput::make('kode_transaksi')
                            ->label('Kode Transaksi')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('jenis_transaksi')
                            ->label('Jenis Transaksi')
                            ->options(Transaksi::getJenisOptions())
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('anggota_id')
                            ->label('Anggota')
                            ->relationship('anggota', 'nama')
                            ->searchable()
                            ->disabled()
                            ->required(),

                        Forms\Components\Select::make('simpanan_id')
                            ->label('Simpanan')
                            ->relationship('simpanan', 'no_simpanan')
                            ->disabled()
                            ->visible(fn ($record) => $record?->isSimpanan()),

                        Forms\Components\Select::make('pinjaman_id')
                            ->label('Pinjaman')
                            ->relationship('pinjaman', 'no_pinjaman')
                            ->disabled()
                            ->visible(fn ($record) => $record?->isPinjaman()),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Keuangan')
                    ->schema([
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->required(),

                        Forms\Components\TextInput::make('saldo_sebelum')
                            ->label('Saldo Sebelum')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),

                        Forms\Components\TextInput::make('saldo_sesudah')
                            ->label('Saldo Sesudah')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(Transaksi::getStatusOptions())
                            ->disabled()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('admin_id')
                            ->label('Diproses Oleh')
                            ->relationship('admin', 'name')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('diverifikasi_pada')
                            ->label('Diverifikasi Pada')
                            ->disabled(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Kode transaksi disalin!')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('jenis_transaksi')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Transaksi::getJenisOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Transaksi::JENIS_SIMPANAN => 'success',
                        Transaksi::JENIS_PENARIKAN_SIMPANAN => 'warning',
                        Transaksi::JENIS_PINJAMAN => 'info',
                        Transaksi::JENIS_PEMBAYARAN_PINJAMAN => 'primary',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Anggota')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('anggota.no_anggota')
                    ->label('No. Anggota')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('simpanan.no_simpanan')
                    ->label('No. Simpanan')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('pinjaman.no_pinjaman')
                    ->label('No. Pinjaman')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable()
                    ->color(fn (Transaksi $record): string =>
                        $record->tipe_mutasi === 'Kredit' ? 'success' : 'danger'
                    )
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('tipe_mutasi')
                    ->label('Mutasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Kredit' => 'success',
                        'Debit' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Transaksi::getStatusOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Transaksi::STATUS_SUKSES => 'success',
                        Transaksi::STATUS_PENDING => 'warning',
                        Transaksi::STATUS_GAGAL => 'danger',
                        Transaksi::STATUS_MENUNGGU_VERIFIKASI => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Admin')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->options(Transaksi::getJenisOptions())
                    ->multiple(),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options(Transaksi::getStatusOptions())
                    ->multiple(),

                Filter::make('simpanan')
                    ->label('Transaksi Simpanan')
                    ->query(fn (Builder $query): Builder => $query->simpanan()),

                Filter::make('pinjaman')
                    ->label('Transaksi Pinjaman')
                    ->query(fn (Builder $query): Builder => $query->pinjaman()),

                Tables\Filters\TrashedFilter::make(),

                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Dari ' . \Carbon\Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Sampai ' . \Carbon\Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tidak ada bulk actions karena hanya view
            ]);
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
            'index' => Pages\ListTransaksis::route('/'),
            'view' => Pages\ViewTransaksi::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    // Disable create and edit
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
