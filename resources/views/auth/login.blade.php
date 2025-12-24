<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Credi Q - Gestión de Turnos</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #000f93 35%, #E41E26 50%, #8B0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .login-container {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 400px;
            animation: fadeInUp 0.8s ease-in-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-header {
            background: linear-gradient(90deg, #000f93, #ca181e);
            text-align: center;
            color: #fff;
            padding: 2rem 1rem 1rem 1rem;
        }

        .login-header img {
            width: 300px;
            margin-bottom: 0.5rem;
        }

        .login-header h2 {
            font-weight: 700;
            font-size: 1.6rem;
            margin: 0;
        }

        .login-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #444;
        }

        .form-control:focus {
            border-color: #E41E26;
            box-shadow: 0 0 0 0.2rem rgba(228, 30, 38, 0.25);
        }

        .btn-crediq {
            background: linear-gradient(90deg,  #000293, #750307);
            border: none;
            font-weight: 600;
            transition: 0.3s;
            color: #fff;
        }

        .btn-crediq:hover {
            background: linear-gradient(90deg, #b7151b, #00386d);
            transform: translateY(-1px);
        }

        .footer {
            text-align: center;
            color: #777;
            font-size: 0.85rem;
            padding: 1rem;
            background-color: #f8f9fa;
        }

        .alert {
            font-size: 0.9rem;
        }

        @media (max-width: 450px) {
            .login-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('images/crediq-logo.png') }}" alt="Credi Q Logo">
            <h2>Gestión de Turnos</h2>
        </div>

        <div class="login-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="usuario@crediq.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="Contraseña">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Recordarme</label>
                </div>

                <button type="submit" class="btn btn-crediq w-100 py-2">Iniciar Sesión</button>
            </form>
        </div>

        <div class="footer">
            © {{ date('Y') }} <strong>Credi Q</strong> | Sistema de Gestión de Turnos
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
