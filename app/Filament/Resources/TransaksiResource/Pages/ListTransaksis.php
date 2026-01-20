<?php

namespace App\Filament\Resources\TransaksiResource\Pages;

use App\Filament\Resources\TransaksiResource;
use App\Models\Transaksi;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTransaksis extends ListRecords
{
    protected static string $resource = TransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action untuk create
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua Transaksi'),

            'simpanan' => Tab::make('Transaksi Simpanan')
                ->modifyQueryUsing(fn (Builder $query) => $query->simpanan())
                ->badge(Transaksi::query()->simpanan()->count()),

            'pinjaman' => Tab::make('Transaksi Pinjaman')
                ->modifyQueryUsing(fn (Builder $query) => $query->pinjaman())
                ->badge(Transaksi::query()->pinjaman()->count()),

            'sukses' => Tab::make('Sukses')
                ->modifyQueryUsing(fn (Builder $query) => $query->sukses())
                ->badge(Transaksi::query()->sukses()->count())
                ->badgeColor('success'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->pending())
                ->badge(Transaksi::query()->pending()->count())
                ->badgeColor('warning'),
        ];
    }
}
