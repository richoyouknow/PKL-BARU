<?php

namespace App\Filament\Widgets;

use App\Models\Transaksi;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class TransaksiChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Nominal Transaksi Sukses Bulanan';

    protected static ?string $pollingInterval = '300s';

    protected static string $color = 'info';

    protected function getData(): array
    {
        // Cache chart data selama 30 menit (1800 detik)
        $data = Cache::remember('stats_transaksi_chart_data', 1800, function () {
            $months = [];
            $totals = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $months[] = $date->translatedFormat('F Y');

                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();

                // Sum jumlah transaksi sukses pada bulan & tahun tersebut (sargable query)
                $sum = Transaksi::where('status', 'sukses')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->sum('jumlah');

                $totals[] = (float) $sum;
            }

            return [
                'months' => $months,
                'totals' => $totals,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaksi Sukses',
                    'data' => $data['totals'],
                    'fill' => 'start',
                ],
            ],
            'labels' => $data['months'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
