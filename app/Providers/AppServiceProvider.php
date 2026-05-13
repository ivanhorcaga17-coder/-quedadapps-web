<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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

        if (! app()->runningInConsole()) {
            URL::forceRootUrl(request()->getSchemeAndHttpHost());
        }

        $manifestPath = config('vite.manifest', 'public/build/manifest.json');
        $buildPath = dirname($manifestPath);
        $publicBuildPath = (string) config(
            'vite.build_path',
            str_starts_with($buildPath, 'public/') ? substr($buildPath, 7) : $buildPath
        );
        $hotFile = config('vite.hot_file', 'public/hot');
        $publicHotFile = str_starts_with($hotFile, 'public/')
            ? public_path(substr($hotFile, 7))
            : public_path($hotFile);

        Vite::useBuildDirectory($publicBuildPath);
        Vite::useManifestFilename(basename($manifestPath));
        Vite::useHotFile($publicHotFile);

        view()->share('sports', [
            'Fútbol',
            'Fútbol sala',
            'Fútbol 7',
            'Fútbol 11',
            'Baloncesto',
            'Baloncesto 3x3',
            'Pádel',
            'Tenis',
            'Tenis de mesa',
            'Voleibol',
            'Vóley playa',
            'Balonmano',
            'Waterpolo',
            'Rugby',
            'Rugby 7',
            'Hockey',
            'Hockey hierba',
            'Hockey patines',
            'Béisbol',
            'Softbol',
            'Frontón',
            'Pickleball',
            'Squash',
            'Bádminton',
            'Golf',
            'Mini golf',
            'Salir a correr',
            'Caminar',
            'Ciclismo',
            'Senderismo',
            'Natación',
            'Crossfit',
            'Yoga',
            'Pilates',
            'Gimnasio',
            'Entrenamiento funcional',
            'Calistenia',
            'Trail running',
            'Triatlón',
            'Patinaje',
            'Skate',
            'Longboard',
            'Surf',
            'Bodyboard',
            'Kitesurf',
            'Windsurf',
            'Paddle surf',
            'Piragüismo',
            'Kayak',
            'Remo',
            'Escalada',
            'Escalada indoor',
            'Barranquismo',
            'Esquí',
            'Snowboard',
            'Artes marciales',
            'Judo',
            'Karate',
            'Taekwondo',
            'Boxeo',
            'Kickboxing',
            'Muay thai',
            'Ajedrez',
            'Petanca',
            'Dardos',
            'Ultimate frisbee',
        ]);

        view()->share('googleMapsApiKey', config('services.google_maps.key'));
        view()->share('currentYear', now()->year);
    }
}
