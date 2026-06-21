<?php

namespace App\Filament\Widgets;

use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Transaksi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Cache selama 5 menit
        $totalAnggota = Cache::remember('stats_total_anggota', 300, function () {
            return Anggota::count();
        });

        $totalSimpanan = Cache::remember('stats_total_simpanan', 300, function () {
            return Simpanan::sum('saldo');
        });

        $totalPinjaman = Cache::remember('stats_total_pinjaman', 300, function () {
            return Pinjaman::sum('saldo_pinjaman');
        });

        $totalTransaksi = Cache::remember('stats_total_transaksi', 300, function () {
            return Transaksi::sum('jumlah');
        });

        return [
            Stat::make('Total Anggota', number_format($totalAnggota, 0, ',', '.'))
                ->description('Anggota terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Simpanan', 'Rp ' . number_format($totalSimpanan, 0, ',', '.'))
                ->description('Saldo simpanan')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Total Pinjaman', 'Rp ' . number_format($totalPinjaman, 0, ',', '.'))
                ->description('Saldo pinjaman aktif')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),

            Stat::make('Total Transaksi', 'Rp ' . number_format($totalTransaksi, 0, ',', '.'))
                ->description('Nominal transaksi')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),
        ];
    }
}
