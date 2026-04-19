<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Quedadapps</title>

    <link rel="icon" type="image/png" href="{{ asset('frontend/img/QuedadAppsWeb.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-white text-[#111]">

    @include('partials.header')

    <!-- HERO -->
    <section class="text-center py-20 md:py-28 lg:py-32 px-6 max-w-3xl mx-auto">

        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6">
            ORGANIZA UN PARTIDO CUANDO QUIERAS
        </h1>

        <p class="text-lg md:text-xl text-gray-600 max-w-xl mx-auto mb-12 leading-relaxed">
            Nunca antes fue tan fácil quedar con tus amigos o gente desconocida para practicar tu deporte favorito.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-6">
            <a href="{{ route('buscar') }}" class="inline-flex justify-center bg-black text-white px-10 py-4 rounded-xl text-lg font-semibold hover:bg-gray-900 transition">
                Buscar partidas
            </a>

            <a href="{{ route('partidas.create') }}" class="inline-flex justify-center bg-gray-100 text-black px-10 py-4 rounded-xl text-lg font-semibold hover:bg-gray-200 transition">
                Crear partidas
            </a>
        </div>
    </section>

    @include('partials.footer-banner')

</body>
</html>
