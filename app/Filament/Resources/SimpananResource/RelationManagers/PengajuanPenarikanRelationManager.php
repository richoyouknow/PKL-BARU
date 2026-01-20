<?php

namespace App\Filament\Resources\SimpananResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPenarikan;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class PengajuanPenarikanRelationManager extends RelationManager
{
    protected static string $relationship = 'pengajuanPenarikan';

    protected static ?string $title = 'Riwayat Pengajuan Penarikan';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jumlah')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->maxValue(fn () => $this->ownerRecord->saldo),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('anggota.nama')
                    ->label('Nama Anggota')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah Penarikan')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('alasan')
                    ->label('Alasan')
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'menunggu',
                        'primary' => 'diproses',
                        'success' => 'disetujui',
                        'danger' => 'ditolak',
                    ]),

                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Diverifikasi Oleh')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('disetujui_pada')
                    ->label('Tanggal Verifikasi')
                    ->dateTime('d/m/Y H:i')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('catatan_admin')
                    ->label('Catatan Admin')
                    ->default('-')
                    ->limit(30)
                    ->toggleable()
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (!$state || strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'menunggu' => 'Menunggu',
                        'diproses' => 'Diproses',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                // Tidak perlu create action karena pengajuan dibuat dari sisi anggota
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Detail Pengajuan Penarikan')
                    ->form([
                        Forms\Components\Section::make('Informasi Pengajuan')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Tanggal Pengajuan')
                                    ->content(fn ($record) => $record->created_at->translatedFormat('d F Y H:i:s')),

                                Forms\Components\Placeholder::make('anggota')
                                    ->label('Nama Anggota')
                                    ->content(fn ($record) => $record->anggota->nama ?? '-'),

                                Forms\Components\Placeholder::make('jumlah')
                                    ->label('Jumlah Penarikan')
                                    ->content(fn ($record) => 'Rp ' . number_format($record->jumlah, 0, ',', '.')),

                                Forms\Components\Placeholder::make('alasan')
                                    ->label('Alasan Penarikan')
                                    ->content(fn ($record) => $record->alasan ?? '-'),

                                Forms\Components\Placeholder::make('status')
                                    ->label('Status')
                                    ->content(fn ($record) => $record->status_text),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Informasi Verifikasi')
                            ->schema([
                                Forms\Components\Placeholder::make('admin')
                                    ->label('Diverifikasi Oleh')
                                    ->content(fn ($record) => $record->admin->name ?? '-'),

                                Forms\Components\Placeholder::make('disetujui_pada')
                                    ->label('Tanggal Verifikasi')
                                    ->content(fn ($record) => $record->disetujui_pada?->translatedFormat('d F Y H:i:s') ?? '-'),

                                Forms\Components\Placeholder::make('catatan_admin')
                                    ->label('Catatan Admin')
                                    ->content(fn ($record) => $record->catatan_admin ?? '-'),
                            ])
                            ->columns(2)
                            ->visible(fn ($record) => $record->status !== 'menunggu'),
                    ]),

                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'menunggu')
                    ->modalHeading('Verifikasi Pengajuan Penarikan')
                    ->modalWidth('2xl')
                    ->form(function ($record) {
                        $simpanan = $this->ownerRecord;

                        return [
                            Forms\Components\Section::make('Informasi Pengajuan')
                                ->schema([
                                    Forms\Components\Placeholder::make('info_anggota')
                                        ->label('Nama Anggota')
                                        ->content($record->anggota->nama ?? '-'),

                                    Forms\Components\Placeholder::make('info_saldo')
                                        ->label('Saldo Saat Ini')
                                        ->content('Rp ' . number_format($simpanan->saldo, 0, ',', '.')),

                                    Forms\Components\Placeholder::make('info_jumlah')
                                        ->label('Jumlah Penarikan')
                                        ->content('Rp ' . number_format($record->jumlah, 0, ',', '.'))
                                        ->extraAttributes([
                                            'class' => 'text-lg font-bold'
                                        ]),

                                    Forms\Components\Placeholder::make('info_saldo_akhir')
                                        ->label('Saldo Setelah Penarikan')
                                        ->content('Rp ' . number_format($simpanan->saldo - $record->jumlah, 0, ',', '.'))
                                        ->extraAttributes([
                                            'class' => fn() => ($simpanan->saldo - $record->jumlah) < 0 ? 'text-red-600 font-bold' : 'text-green-600 font-bold'
                                        ]),

                                    Forms\Components\Placeholder::make('info_alasan')
                                        ->label('Alasan Penarikan')
                                        ->content($record->alasan ?? '-'),

                                    Forms\Components\Placeholder::make('info_tanggal')
                                        ->label('Tanggal Pengajuan')
                                        ->content($record->created_at->translatedFormat('d F Y H:i:s')),
                                ])
                                ->columns(2),

                            Forms\Components\Section::make('Verifikasi')
                                ->schema([
                                    Forms\Components\Select::make('status_verifikasi')
                                        ->label('Keputusan')
                                        ->options([
                                            'disetujui' => 'Setujui',
                                            'ditolak' => 'Tolak',
                                        ])
                                        ->required()
                                        ->reactive()
                                        ->default('disetujui'),

                                    Forms\Components\Textarea::make('catatan_admin')
                                        ->label('Catatan Admin')
                                        ->placeholder('Isi catatan verifikasi (opsional)')
                                        ->rows(3)
                                        ->maxLength(500),
                                ])
                        ];
                    })
                    ->action(function ($record, array $data) {
                        $simpanan = $this->ownerRecord;

                        DB::beginTransaction();
                        try {
                            if ($data['status_verifikasi'] === 'disetujui') {
                                // Validasi saldo
                                if ($simpanan->saldo < $record->jumlah) {
                                    throw new \Exception('Saldo tidak mencukupi untuk penarikan ini!');
                                }

                                // Update saldo simpanan
                                $simpanan->update([
                                    'saldo' => $simpanan->saldo - $record->jumlah
                                ]);

                                // Update status pengajuan
                                $record->update([
                                    'status' => 'disetujui',
                                    'admin_id' => auth()->id(),
                                    'catatan_admin' => $data['catatan_admin'] ?? null,
                                    'disetujui_pada' => now(),
                                ]);

                                DB::commit();

                                Notification::make()
                                    ->title('Pengajuan Disetujui')
                                    ->body('Penarikan sebesar Rp ' . number_format($record->jumlah, 0, ',', '.') . ' telah disetujui.')
                                    ->success()
                                    ->send();
                            } else {
                                // Update status pengajuan menjadi ditolak
                                $record->update([
                                    'status' => 'ditolak',
                                    'admin_id' => auth()->id(),
                                    'catatan_admin' => $data['catatan_admin'] ?? null,
                                ]);

                                DB::commit();

                                Notification::make()
                                    ->title('Pengajuan Ditolak')
                                    ->body('Pengajuan penarikan telah ditolak.')
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
            ])
            ->bulkActions([
                // Tidak ada bulk action
            ])
            ->defaultSort('created_at', 'desc');
    }
}
