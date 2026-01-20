<?php

namespace App\Filament\Resources\AnggotaResource\Pages;

use App\Filament\Resources\AnggotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAnggota extends EditRecord
{
    protected static string $resource = AnggotaResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Anggota berhasil diperbarui')
            ->body(function () {
                $noRegistrasi = $this->record->no_registrasi;
                $nama = $this->record->nama;
                $status = $this->record->grup_wilayah;
                $noAnggota = $this->record->no_anggota ?: 'Belum ada';

                return "No. Registrasi: {$noRegistrasi}<br>Nama: {$nama}<br>Status: {$status}<br>No. Anggota: {$noAnggota}";
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
