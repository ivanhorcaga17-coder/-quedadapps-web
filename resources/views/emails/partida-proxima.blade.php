<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partida próxima</title>
    <style>
        body {
            margin: 0;
            padding: 24px;
            background: #f5f5f4;
            font-family: Arial, sans-serif;
            color: #111111;
        }

        .card {
            max-width: 560px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #d6d3d1;
            border-radius: 24px;
            overflow: hidden;
        }

        .header {
            padding: 28px 32px;
            background: #111111;
            color: #ffffff;
        }

        .header small {
            display: block;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: #d6d3d1;
            margin-bottom: 10px;
        }

        .content {
            padding: 32px;
        }

        .content h1 {
            margin: 0 0 12px;
            font-size: 28px;
        }

        .content p {
            margin: 0 0 16px;
            color: #44403c;
            line-height: 1.7;
        }

        .details {
            margin: 24px 0;
            padding: 20px;
            border-radius: 18px;
            background: #f5f5f4;
            border: 1px solid #e7e5e4;
        }

        .details strong {
            color: #111111;
        }

        .button {
            display: inline-block;
            margin-top: 8px;
            padding: 14px 22px;
            border-radius: 999px;
            background: #111111;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: bold;
        }

        .footer {
            padding: 20px 32px 28px;
            color: #78716c;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <small>QuedadApps</small>
            <strong>Tu partida empieza en {{ $minutesBefore }} minutos</strong>
        </div>

        <div class="content">
            <h1>{{ $partida->deporte }}</h1>
            <p>Hola {{ $usuario->name }}, te recordamos que una de tus partidas está a punto de empezar.</p>

            <div class="details">
                <p><strong>Deporte:</strong> {{ $partida->deporte }}</p>
                <p><strong>Fecha:</strong> {{ $partida->fecha->format('d/m/Y') }}</p>
                <p><strong>Hora:</strong> {{ $partida->fecha->format('H:i') }}</p>
                <p><strong>Ubicación:</strong> {{ $partida->lugar }}</p>
            </div>

            <a href="{{ route('partidas.showPage', $partida) }}" class="button">Ver partida</a>
        </div>

        <div class="footer">
            QuedadApps · Gestiona tus quedadas deportivas desde cualquier lugar.
        </div>
    </div>
</body>
</html>
