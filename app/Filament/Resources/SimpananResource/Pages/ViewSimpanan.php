<?php

namespace App\Filament\Resources\SimpananResource\Pages;

use App\Filament\Resources\SimpananResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSimpanan extends ViewRecord
{
    protected static string $resource = SimpananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
