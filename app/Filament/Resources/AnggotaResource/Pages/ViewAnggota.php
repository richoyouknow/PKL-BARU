<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Registrasi')
                    ->schema([
                        TextEntry::make('no_registrasi')
                            ->label('No. Registrasi')
                            ->badge()
                            ->color('primary'),

                        TextEntry::make('no_anggota')
                            ->label('No. Anggota')
                            ->badge()
                            ->color(fn ($record) => $record->grup_wilayah === 'Anggota' ? 'success' : 'gray')
                            ->placeholder('-'),

                        TextEntry::make('grup_wilayah')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'Anggota' => 'success',
                                'Calon Anggota' => 'warning',
                                'Nasabah Non Anggota' => 'gray',
                                default => 'gray',
                            }),

                        TextEntry::make('tanggal_daftar')
                            ->date('d/m/Y'),

                        TextEntry::make('created_at')
                            ->label('Dibuat Pada')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(3),

                // Tambahkan sections lain sesuai kebutuhan
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
