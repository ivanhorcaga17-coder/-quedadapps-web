<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function buscar(Request $request)
    {
        $sports = $this->availableSports();
        $joinedPartidaIds = $request->user()
            ? $request->user()->asistencias()->pluck('partida_id')->all()
            : [];

        $partidas = Partida::query()
            ->with(['creador', 'asistencias.usuario'])
            ->withCount('asistencias')
            ->when($request->filled('deporte'), fn ($query) => $query->where('deporte', $request->string('deporte')))
            ->when($request->filled('lugar'), fn ($query) => $query->where('lugar', 'like', '%' . $request->string('lugar') . '%'))
            ->when($request->filled('fecha'), fn ($query) => $query->whereDate('fecha', $request->input('fecha')))
            ->orderBy('fecha')
            ->paginate(9)
            ->withQueryString();

        return view('frontend.buscar', [
            'partidas' => $partidas,
            'filtros' => $request->only(['deporte', 'lugar', 'fecha']),
            'sports' => $sports,
            'googleMapsApiKey' => config('services.google_maps.key'),
            'joinedPartidaIds' => $joinedPartidaIds,
        ]);
    }

    public function calendario()
    {
        $partidas = Partida::query()
            ->orderBy('fecha')
            ->get()
            ->map(fn (Partida $partida) => [
                'id' => $partida->id,
                'title' => $partida->titulo,
                'start' => $partida->fecha->toIso8601String(),
                'url' => route('partidas.showPage', $partida),
            ]);

        return view('frontend.calendario', [
            'calendarEvents' => $partidas,
        ]);
    }

    public function acerca()
    {
        return view('frontend.acerca');
    }

    public function asistencia(Request $request)
    {
        $user = $request->user();

        $misAsistencias = collect();
        $misPartidasIds = [];

        if ($user) {
            $misAsistencias = $user->asistencias()
                ->with('partida.creador')
                ->latest()
                ->get();

            $misPartidasIds = $misAsistencias->pluck('partida_id')->all();
        }

        $proximasPartidas = Partida::query()
            ->with('creador')
            ->withCount('asistencias')
            ->where('fecha', '>=', now()->subDay())
            ->orderBy('fecha')
            ->limit(12)
            ->get();

        return view('frontend.asistencia', [
            'misAsistencias' => $misAsistencias,
            'misPartidasIds' => $misPartidasIds,
            'proximasPartidas' => $proximasPartidas,
        ]);
    }

    protected function availableSports(): array
    {
        return [
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
            'Running',
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
        ];
    }
}
