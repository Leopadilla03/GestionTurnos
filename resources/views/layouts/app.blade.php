<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Credi Q | Sistema de Gestión de Turnos')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #004B93, #002f5c);
            position: fixed;
            top: 0;
            left: 0;
            padding: 1.5rem 1rem;
            color: white;
        }
        .sidebar h4 {
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-align: center;
        }
        .sidebar a {
            display: block;
            color: #ffffffcc;
            text-decoration: none;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            transition: 0.3s;
            font-weight: 500;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #E41E26;
            color: #fff;
        }
        .main-content {
            margin-left: 260px;
            padding: 2rem;
        }
        .logout-btn {
            margin-top: 3rem;
            background: #E41E26;
            color: white;
            width: 100%;
            border: none;
            font-weight: 600;
            border-radius: 10px;
            padding: 0.6rem;
        }
        .logout-btn:hover {
            background: #b7151b;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Credi Q</h4>

        <p class="text-center small mb-4">
            Rol: <strong>{{ Auth::user()->rol ?? '—' }}</strong>
        </p>

        {{-- 🔹 Mostrar menú según rol --}}
        @if(Auth::check() && Auth::user()->rol === 'administrador')
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Panel Principal
            </a>

            <hr class="text-white">

            <a href="{{ route('admin.usuarios') }}" class="{{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
                <i class="bi bi-people-fill me-2"></i> Gestión de Usuarios
            </a>

            <a href="{{ route('admin.departamentos') }}" class="{{ request()->routeIs('admin.departamentos*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3-fill me-2"></i> Departamentos
            </a>

            <a href="{{ route('admin.ventanillas') }}" class="{{ request()->routeIs('admin.ventanillas*') ? 'active' : '' }}">
                <i class="bi bi-window-sidebar me-2"></i> Ventanillas
            </a>

            <a href="{{ route('admin.asignaciones') }}" class="{{ request()->routeIs('admin.asignaciones.ventanillas*') ? 'active' : '' }}">
                <i class="bi bi-person-check-fill me-2"></i> Asignaciones
            </a>

            <a href="{{ route('admin.reportes') }}" class="{{ request()->routeIs('admin.reportes*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow me-2"></i> Reportes
            </a>

            <a href="{{ route('admin.sociedad') }}" class="{{ request()->routeIs('admin.sociedad*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i> Sociedades
            </a>

            <a href="{{ route('admin.sucursales') }}" class="{{ request()->routeIs('admin.sucursales*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 me-2"></i> Sucursales
            </a>

            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </li>


        @endif 

         {{-- 🔹 Menú para OPERADOR --}}
            @if(Auth::user() && Auth::user()->rol === 'operador')
            
                {{-- ======== OPERADOR MENU ======== --}}
            <a href="{{ route('operador.panel') }}" class="{{ request()->routeIs('operador.panel*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Panel Principal
            </a>
            <hr>

            <hr>
            <a href="{{ route('operador.historial') }}" class="{{ request()->routeIs('operador.historial*') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Historial de Turnos
            </a>

            {{-- 🔹 Botón de cierre de sesión --}}
            <form action="{{ route('logout') }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </button>
            </form>
        @endif
    </div>

    {{-- 🔹 Contenido principal --}}
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>