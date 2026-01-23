<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AnggotaResource;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;

class ViewAnggota extends ViewRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Grid::make(3)
                    ->schema([
                        Section::make('Foto Anggota')
                            ->columnSpan(1)
                            ->schema([
                                ImageEntry::make('foto')
                                    ->label('')
                                    ->disk('public')
                                    ->defaultImageUrl(asset('images/default-avatar.png'))
                                    ->circular()
                                    ->size(200)
                                    ->alignCenter(),
                            ]),

                        Section::make('Data Registrasi')
                            ->columnSpan(2)
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('no_registrasi')
                                            ->label('No. Registrasi')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('primary'),

                                        TextEntry::make('no_anggota')
                                            ->label('No. Anggota')
                                            ->weight('bold')
                                            ->size('lg')
                                            ->color('success')
                                            ->placeholder('-'),

                                        TextEntry::make('tanggal_daftar')
                                            ->label('Tanggal Daftar')
                                            ->date('d/m/Y')
                                            ->placeholder('-'),

                                        TextEntry::make('grup_wilayah')
                                            ->label('Grup Wilayah')
                                            ->badge()
                                            ->color(fn(string $state): string => match ($state) {
                                                'Karyawan Koperasi' => 'success',
                                                'Karyawan Tetap' => 'success',

                                                'Karyawan PKWT' => 'warning',
                                                'Outsourcing' => 'warning',

                                                'Non Karyawan' => 'gray',
                                                'Pensiun' => 'gray',
                                                'Petugas Gudang Pengolah' => 'info',

                                                default => 'gray',
                                            }),

                                    ]),
                            ]),
                    ]),

                Section::make('Informasi Akun')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('user.name')
                                    ->label('Nama User'),

                                TextEntry::make('user.email')
                                    ->label('Email')
                                    ->copyable()
                                    ->icon('heroicon-m-envelope'),
                            ]),
                    ]),

                Section::make('Data Pribadi')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('nama')
                            ->label('Nama Lengkap')
                            ->weight('bold')
                            ->columnSpanFull(),

                        TextEntry::make('alamat')
                            ->label('Alamat')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('no_telepon')
                            ->label('No. Telepon')
                            ->icon('heroicon-m-phone')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('jenis_kelamin')
                            ->label('Jenis Kelamin')
                            ->placeholder('-'),

                        TextEntry::make('tempat_lahir')
                            ->label('Tempat Lahir')
                            ->placeholder('-'),

                        TextEntry::make('tanggal_lahir')
                            ->label('Tanggal Lahir')
                            ->date('d/m/Y')
                            ->placeholder('-'),

                        TextEntry::make('agama')
                            ->label('Agama')
                            ->placeholder('-'),
                    ]),

                Section::make('Data Identitas')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('jenis_identitas')
                            ->label('Jenis Identitas')
                            ->placeholder('-'),

                        TextEntry::make('no_identitas')
                            ->label('No. Identitas')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('berlaku_sampai')
                            ->label('Berlaku Sampai')
                            ->date('d/m/Y')
                            ->placeholder('-'),
                    ]),

                Section::make('Data Pekerjaan & Keuangan')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('pekerjaan')
                            ->label('Pekerjaan')
                            ->placeholder('-'),

                        TextEntry::make('nama_pasangan')
                            ->label('Nama Pasangan')
                            ->placeholder('-'),

                        TextEntry::make('pendapatan')
                            ->label('Pendapatan')
                            ->money('IDR')
                            ->placeholder('-'),

                        TextEntry::make('alamat_kantor')
                            ->label('Alamat Kantor')
                            ->placeholder('-'),
                    ]),

                Section::make('Informasi Tambahan')
                    ->schema([
                        TextEntry::make('keterangan')
                            ->label('Keterangan')
                            ->placeholder('-')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d/m/Y H:i'),

                                TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->dateTime('d/m/Y H:i'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
