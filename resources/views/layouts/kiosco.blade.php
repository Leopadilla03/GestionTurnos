<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosco de Turnos - Credi Q</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">
    @php
        $codigoPais = strtoupper($pais ?? 'HN');
        $paletas = [
            'HN' => [
                'gradientStart' => '#0b4f9c', // azul bandera Honduras
                'gradientEnd'   => '#1e88e5',
                'accent'        => '#0b4f9c',
                'accentSoft'    => '#d6e9ff'
            ],
            'CR' => [
                'gradientStart' => '#b71c1c', // rojo más oscuro CR
                'gradientEnd'   => '#002b5c', // azul más oscuro CR
                'accent'        => '#b71c1c',
                'accentSoft'    => '#f5c7c7'
            ],
        ];
        $tema = $paletas[$codigoPais] ?? $paletas['HN'];
    @endphp
    <style>
        :root {
            --grad-start: {{ $tema['gradientStart'] }};
            --grad-end: {{ $tema['gradientEnd'] }};
            --accent: {{ $tema['accent'] }};
            --accent-soft: {{ $tema['accentSoft'] }};
        }
        * {
            font-family: 'Poppins', sans-serif;
        }
        body {
            background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        .kiosco-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 3rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
            backdrop-filter: blur(10px);
        }
        .kiosco-title {
            color: #4a5568;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .subtitle {
            color: #718096;
            font-size: 1.2rem;
            font-weight: 500;
        }
        .country-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
            color: white;
            padding: 0.5rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="kiosco-container">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>