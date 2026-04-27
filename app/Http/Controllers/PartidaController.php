<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PartidaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'preview', 'finalize', 'destroy']);
    }

    public function index(Request $request)
    {
        $partidas = Partida::with('creador')->latest('fecha')->get();

        return response()->json($partidas);
    }

    public function create()
    {
        return view('frontend.crear', [
            'sports' => $this->availableSports(),
            'googleMapsApiKey' => config('services.google_maps.key'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deporte' => ['required', 'string', Rule::in($this->availableSports())],
            'fecha' => 'required|date',
            'lugar' => 'required|string|max:255',
            'max_jugadores' => 'required|integer|min:1',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $imagen = $request->hasFile('imagen')
            ? $request->file('imagen')->store('temp/partidas', 'public')
            : $this->resolveDefaultImage($validated['deporte']);

        $pendingPartida = [
            'titulo' => $validated['deporte'] . ' en ' . $validated['lugar'],
            'deporte' => $validated['deporte'],
            'fecha' => $validated['fecha'],
            'lugar' => $validated['lugar'],
            'max_jugadores' => $validated['max_jugadores'],
            'imagen' => $imagen,
            'image_preview_url' => $this->resolveImagePreviewUrl($imagen),
        ];

        $request->session()->put('pending_partida', $pendingPartida);

        return redirect()->route('partidas.preview');
    }

    public function show(Partida $partida)
    {
        return response()->json($partida->load('creador'));
    }

    public function preview(Request $request)
    {
        $pendingPartida = $request->session()->get('pending_partida');

        abort_unless($pendingPartida, 404);

        return view('frontend.confirmar-creacion', [
            'pendingPartida' => $pendingPartida,
            'googleMapsApiKey' => config('services.google_maps.key'),
        ]);
    }

    public function finalize(Request $request)
    {
        $pendingPartida = $request->session()->get('pending_partida');

        abort_unless($pendingPartida, 404);

        $validated = $request->validate([
            'jugadores' => 'nullable|string|max:1000',
        ]);

        $imagen = $this->movePendingImageToFinalLocation(
            $pendingPartida['imagen'],
            $pendingPartida['deporte']
        );

        $partida = $request->user()->partidasCreadas()->create([
            'titulo' => $pendingPartida['titulo'],
            'deporte' => $pendingPartida['deporte'],
            'fecha' => $pendingPartida['fecha'],
            'lugar' => $pendingPartida['lugar'],
            'max_jugadores' => $pendingPartida['max_jugadores'],
            'imagen' => $imagen,
        ]);

        $this->syncInitialPlayers($partida, $request->user(), $validated['jugadores'] ?? null);

        $request->session()->forget('pending_partida');

        return redirect()->route('partidas.showPage', $partida)
            ->with('success', 'Partida creada correctamente.');
    }

    public function confirmar(Partida $partida)
    {
        return $this->showPage($partida);
    }

    public function showPage(Partida $partida)
    {
        $partida->load([
            'creador',
            'asistencias.usuario',
            'chatMessages.usuario',
        ]);

        return view('frontend.confirmacion', [
            'partida' => $partida,
            'googleMapsApiKey' => config('services.google_maps.key'),
            'authUserJoined' => auth()->check()
                ? $partida->asistencias->contains(fn ($asistencia) => $asistencia->usuario_id === auth()->id())
                : false,
        ]);
    }

    public function update(Request $request, Partida $partida)
    {
        $validated = $request->validate([
            'deporte' => ['sometimes', 'required', 'string', 'max:255', Rule::in($this->availableSports())],
            'fecha' => 'sometimes|required|date',
            'lugar' => 'sometimes|required|string|max:255',
            'max_jugadores' => 'sometimes|required|integer|min:1',
        ]);

        if (isset($validated['deporte'], $validated['lugar'])) {
            $validated['titulo'] = $validated['deporte'] . ' en ' . $validated['lugar'];
        }

        $partida->update($validated);

        return response()->json($partida->fresh('creador'));
    }

    public function destroy(Partida $partida)
    {
        abort_unless(auth()->id() === $partida->creador_id, 403);

        $partida->delete();

        return redirect()->route('buscar')
            ->with('success', 'Tu partida se ha eliminado correctamente.');
    }

    protected function resolveDefaultImage(string $deporte): string
    {
        $normalized = Str::of($deporte)->lower()->ascii()->value();

        $folder = match (true) {
            str_contains($normalized, 'futbol') => 'images/defaults/futbol',
            str_contains($normalized, 'padel') => 'images/defaults/padel',
            default => 'images/defaults/generico',
        };

        $files = collect(glob(public_path($folder . '/*')))
            ->filter(fn ($path) => is_file($path))
            ->values();

        if ($files->isEmpty()) {
            return 'images/defaults/generico/default.svg';
        }

        return Str::after($files->random(), public_path() . '/');
    }

    protected function movePendingImageToFinalLocation(string $imagePath, string $deporte): string
    {
        if (! str_starts_with($imagePath, 'temp/')) {
            return $imagePath;
        }

        $finalPath = 'partidas/' . basename($imagePath);

        if (! Storage::disk('public')->exists($imagePath)) {
            return $this->resolveDefaultImage($deporte);
        }

        if (! Storage::disk('public')->exists($finalPath)) {
            Storage::disk('public')->move($imagePath, $finalPath);
        }

        return $finalPath;
    }

    protected function resolveImagePreviewUrl(string $imagePath): string
    {
        if (str_starts_with($imagePath, 'images/')) {
            return asset($imagePath);
        }

        return asset('storage/' . $imagePath);
    }

    protected function syncInitialPlayers(Partida $partida, User $creator, ?string $rawPlayers): void
    {
        $userIds = collect([$creator->id]);

        $tokens = collect(explode(',', (string) $rawPlayers))
            ->map(fn ($value) => trim($value))
            ->filter();

        foreach ($tokens as $token) {
            $matchedUser = is_numeric($token)
                ? User::find((int) $token)
                : User::query()
                    ->where('nombre', 'like', '%' . $token . '%')
                    ->orWhere('email', 'like', '%' . $token . '%')
                    ->first();

            if ($matchedUser) {
                $userIds->push($matchedUser->id);
            }
        }

        $partida->asistencias()->createMany(
            $userIds->unique()->map(fn ($userId) => [
                'usuario_id' => $userId,
                'estado' => 'confirmado',
            ])->values()->all()
        );
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
