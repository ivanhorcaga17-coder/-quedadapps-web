<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        Carbon::setLocale('es');

        view()->share('sports', [
            'Fútbol',
            'Fútbol sala',
            'Baloncesto',
            'Baloncesto 3x3',
            'Pádel',
            'Tenis',
            'Voleibol',
            'Vóley playa',
            'Salir a correr',
            'Caminar',
            'Ciclismo',
            'Senderismo',
            'Natación',
            'Crossfit',
            'Yoga',
            'Boxeo',
            'Rugby',
            'Bádminton',
            'Escalada',
            'Skate',
        ]);

        view()->share('googleMapsApiKey', config('services.google_maps.key'));
        view()->share('currentYear', now()->year);
    }
}
