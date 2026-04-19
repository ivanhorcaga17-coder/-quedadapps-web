<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Quedadapps')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-black">

    {{-- HEADER GLOBAL --}}
    @include('partials.header')

    {{-- CONTENIDO --}}
    <main class="pt-6 px-10">
        @yield('content')
    </main>

</body>
</html>
