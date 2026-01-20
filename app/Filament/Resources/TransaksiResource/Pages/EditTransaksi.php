<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTransaksi extends EditRecord
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $statusLama = $this->record->status;
        $statusBaru = $data['status'];

        // Set admin_id dan waktu verifikasi jika status berubah ke sukses
        if ($statusBaru === 'sukses' && $statusLama !== 'sukses') {
            $data['admin_id'] = auth()->id();
            $data['diverifikasi_pada'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Jika status diubah dari pending/menunggu_verifikasi ke sukses
        // Update saldo simpanan/pinjaman
        if ($this->record->status === 'sukses' &&
            in_array($this->record->getOriginal('status'), ['pending', 'menunggu_verifikasi'])) {
            $this->record->fresh()->updateSaldo();

            Notification::make()
                ->success()
                ->title('Transaksi Diverifikasi')
                ->body('Saldo telah diperbarui')
                ->send();
        }
    }
}
