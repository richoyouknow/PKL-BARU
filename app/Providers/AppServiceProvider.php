<?php

namespace App\Providers;

use App\Models\Slider;
use App\Models\Pinjaman;
use App\Observers\PinjamanObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pinjaman::observe(PinjamanObserver::class);

                View::composer('*', function ($view) {
            $sliders = Slider::where('is_active', true)
                            ->orderBy('order')
                            ->get();
            $view->with('sliders', $sliders);
        });
    }
}
