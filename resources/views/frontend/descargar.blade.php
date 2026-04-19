<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Descargar Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {

            function isIOS() {
                return /iPhone|iPad|iPod/i.test(navigator.userAgent);
            }

            if (!isIOS() && !localStorage.getItem("apkDownloaded")) {

                fetch("/downloads/QuedadApps.apk")
                    .then(response => {
                        if (response.ok) {

                            localStorage.setItem("apkDownloaded", "true");

                            setTimeout(() => {
                                window.location.href = "/downloads/QuedadApps.apk";
                            }, 250);
                        }
                    })
                    .catch(() => console.warn("Error comprobando el APK"));
            }
        });
    </script>
</head>

<body class="bg-black text-white font-sans">

    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-cloak
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            class="max-w-xl mx-auto mt-6 mb-4 p-4 bg-stone-100 text-stone-900 text-center rounded-lg shadow-lg transition"
        >
            {{ session('success') }}
        </div>
    @endif

    <!-- LOGO CLICABLE + REDONDEADO -->
    <header class="w-full py-10 border-b border-white/10 flex justify-center">
        <a href="{{ route('index') }}">
            <img src="/frontend/img/logotipo2_quedadapps.png" 
                 alt="Quedadapps Logo" 
                 class="w-40 md:w-48 lg:w-56 object-contain rounded-xl hover:opacity-80 transition">
        </a>
    </header>

    <!-- MENSAJE DESCARGA -->
    <section class="text-center mt-16 px-6 max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-4 tracking-wide">GRACIAS POR DESCARGAR QUEDADAPPS</h2>
        <p class="text-white/60 text-lg mb-10 leading-relaxed">
            Tu descarga debería comenzar automáticamente.  
            Si no es así, puedes descargarla manualmente.
        </p>

        <a href="/downloads/QuedadApps.apk" target="_blank" class="inline-flex px-10 py-4 bg-white text-black rounded-xl font-semibold text-lg hover:bg-gray-200 transition">
            Descargar manualmente
        </a>
    </section>

    <!-- VALORACIÓN -->
    <section class="mt-24 px-6 max-w-xl mx-auto" 
             x-data="{ rating: 0, hover: 0, comment: '' }">

        <h3 class="text-2xl font-semibold mb-6 text-center tracking-wide">
            Puntúa nuestra app
        </h3>

        <div class="flex justify-center gap-3 text-5xl mb-8">
            <template x-for="star in 5">
                <span 
                    class="cursor-pointer transition transform duration-200"
                    @mouseover="hover = star"
                    @mouseleave="hover = 0"
                    @click="rating = (rating === star ? 0 : star)"
                    x-text="'★'"
                    :class="{
                        'text-yellow-400 scale-110': star <= hover || star <= rating,
                        'text-gray-700': star > hover && star > rating
                    }"
                ></span>
            </template>
        </div>

        <textarea 
            class="w-full p-5 rounded-xl bg-[#111] text-white placeholder-white/40 border border-white/10 focus:border-white/30 focus:outline-none transition"
            rows="4"
            placeholder="Escribe tu opinión aquí..."
            x-model="comment"
        ></textarea>

        <button 
            class="mt-6 w-full py-4 bg-white text-black rounded-xl font-semibold text-lg hover:bg-gray-200 transition"
            @click="$refs.form.submit()"
        >
            Enviar valoración
        </button>

        <form x-ref="form" method="POST" action="{{ route('reviews.store') }}" class="hidden">
            @csrf
            <input type="hidden" name="rating" :value="rating">
            <input type="hidden" name="comment" :value="comment">
        </form>

    </section>

    <!-- REVIEWS -->
    <section id="reviews" class="mt-24 px-6 max-w-xl mx-auto">
        <h3 class="text-2xl font-semibold mb-8 tracking-wide">Últimas valoraciones</h3>

        @foreach ($reviews as $review)
            <div class="bg-white/5 p-5 rounded-xl mb-5 border border-white/10 backdrop-blur-sm">

                <div class="text-yellow-400 text-xl mb-2">
                    {{ str_repeat('★', $review->rating) }}
                </div>

                <p class="text-white/80 leading-relaxed">{{ $review->comment }}</p>

                <!-- FOTO + NOMBRE -->
                <div class="flex items-center gap-3 mt-4">

                    @if($review->user && $review->user->foto)
                        <img src="/frontend/img/perfiles/{{ $review->user->foto }}" 
                             class="h-8 w-8 rounded-full object-cover border">
                    @else
                        <img src="/frontend/img/perfil.png" 
                             class="h-8 w-8 rounded-full object-cover border">
                    @endif

                    <p class="text-white/80 text-sm">
                        @if($review->user)
                            {{ $review->user->name }}
                        @else
                            Anónimo
                        @endif
                        — {{ $review->created_at->format('Y') }}
                    </p>

                </div>

                <!-- BOTÓN BORRAR -->
                @if(
                    ($review->user_id !== null && Auth::check() && Auth::id() === $review->user_id) ||
                    ($review->user_id === null && !Auth::check() && $review->ip_address === request()->ip())
                )
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}">
                        @csrf
                        @method('DELETE')
                        <button class="mt-3 text-red-400 hover:text-red-300 text-sm">
                            Borrar mi valoración
                        </button>
                    </form>
                @endif

            </div>
        @endforeach

    </section>

</body>
</html>
