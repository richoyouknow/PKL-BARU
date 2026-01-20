<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAnggota extends CreateRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Anggota berhasil dibuat')
            ->body(function () {
                $noRegistrasi = $this->record->no_registrasi;
                $nama = $this->record->nama;
                $noAnggota = $this->record->no_anggota ?: 'Belum ada (bukan status Anggota)';

                return "No. Registrasi: {$noRegistrasi}<br>Nama: {$nama}<br>No. Anggota: {$noAnggota}";
            });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
