<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #0d0d0d;
            font-family: 'Inter', Arial, sans-serif;
            color: #fff;
        }

        .container {
            max-width: 480px;
            margin: 40px auto;
            background: #111;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            border: 1px solid #222;
        }

        /* HEADER */
        .header {
            background: #000;
            padding: 32px;
            text-align: center;
            border-bottom: 1px solid #222;
        }

        .header-title {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #fff;
            text-transform: uppercase;
        }

        /* CONTENT */
        .content {
            padding: 32px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #fff;
        }

        .text {
            font-size: 15px;
            line-height: 1.7;
            color: #ccc;
            margin-bottom: 28px;
            white-space: pre-line;
        }

        /* BUTTON */
        .button {
            display: inline-block;
            background: #fff;
            color: #000 !important;
            padding: 14px 22px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: 0.2s;
        }

        .button:hover {
            background: #e5e5e5;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
            background: #0d0d0d;
            border-top: 1px solid #222;
        }
    </style>
</head>

<body>

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="header-title">Quedadapps</div>
        </div>

        <!-- CONTENT -->
        <div class="content">
            <div class="title">
                {{ $title }}
            </div>

            <div class="text">
                {!! nl2br(e($messageText)) !!}
            </div>

            @if(isset($buttonUrl))
                <div style="text-align:center; margin-top: 25px;">
                    <a href="{{ $buttonUrl }}" class="button">
                        {{ $buttonText ?? 'Abrir enlace' }}
                    </a>
                </div>
            @endif
        </div>

        <!-- FOOTER -->
        <div class="footer">
            © {{ date('Y') }} Quedadapps — Todos los derechos reservados.
        </div>

    </div>

</body>
</html>
