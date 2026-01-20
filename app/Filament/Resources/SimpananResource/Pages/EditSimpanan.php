<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use App\Filament\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimpanan extends EditRecord
{
    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    if ($record->saldo > 0) {
                        throw new \Exception('Tidak dapat menghapus simpanan yang masih memiliki saldo!');
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Simpanan berhasil diperbarui';
    }
}
