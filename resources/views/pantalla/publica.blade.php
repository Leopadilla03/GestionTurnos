<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Turnos | CrediQ</title>

    {{-- Fuentes e iconos --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Animaciones estilo LED / NovoSGA --}}
    <style>
        body {
            margin: 0;
            background: radial-gradient(circle at top, #0a0d57, #000428);
            color: white;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            --accent: #00d5ff;
            --ventColor: #ffe7b3;
            --tipo-normal: #00d5ff;
            --tipo-preferencial: #e9ec0e;
            --sub-bg: rgba(255, 255, 255, 0.12);
            --item-bg: rgba(255, 255, 255, 0.12);
        }

        /* Tema rojo para Costa Rica */
        body.cr {
            background: radial-gradient(circle at top, #3a0000, #0e0000);
            --accent: #ff4d4d;
            --ventColor: #ffd6d6;
            --tipo-normal: #ff6b6b;
            --tipo-preferencial: #f1ef54;
            --sub-bg: rgba(255, 77, 77, 0.08);
            --item-bg: rgba(255, 255, 255, 0.06);
        }

        /* --- Turno principal --- */
        .turno-actual-box {
            text-align: center;
            padding: 40px 20px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.07);
            width: 90%;
            margin: auto;
            margin-top: 40px;
            animation: fadeIn 0.8s ease-out;
        }

        .turno-actual-numero {
            font-size: 10rem;
            font-weight: 800;
            color: var(--accent);
            text-shadow: 0px 0px 25px var(--accent);
            letter-spacing: -5px;
            animation: pulseGlow 1.6s infinite alternate;
        }

        .turno-actual-ventanilla {
            font-size: 3.5rem;
            margin-top: 15px;
            color: var(--ventColor);
            font-weight: 600;
            text-shadow: 0px 0px 10px black;
        }

        /* --- Listas --- */
        .panel {
            width: 90%;
            margin: auto;
            margin-top: 50px;
            padding: 20px;
            background: rgba(255,255,255,0.08);
            border-radius: 15px;
            backdrop-filter: blur(5px);
        }

        .panel h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.2rem;
            font-weight: 600;
        }

        .lista-turnos {
            max-height: 350px;
            overflow: hidden;
            position: relative;
        }

        .turno-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 20px;
            margin-bottom: 8px;
            font-size: 1.8rem;
            background: var(--item-bg);
            border-radius: 10px;
            animation: slideUp 0.6s ease-out;
        }

        .turno-item span {
            color: #00eaff;
            font-weight: 600;
        }

        /* === LISTA DE COLA CON SCROLL === */
        .subturnos {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 8px;
        }

        /* === ITEM DE COLA === */
        .subturno {
            background: var(--sub-bg);
            margin-bottom: 8px;
            padding: 14px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 1.8rem;
            animation: slideUp 0.6s ease-out;
        }

        .tipo.normal { color: var(--tipo-normal); font-weight: 700; }
        .tipo.preferencial { color: var(--tipo-preferencial); font-weight: 700; }


        /* --- Animaciones --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseGlow {
            from { text-shadow: 0px 0px 20px #00d5ff; }
            to { text-shadow: 0px 0px 40px #00ffe7; }
        }
    </style>
</head>

<body @if(isset($pais) && strtoupper($pais) === 'CR') class="cr" @endif>

    {{-- ========================= --}}
    {{-- 🔵 TURNO ACTUAL ANIMADO --}}
    {{-- ========================= --}}
    <div id="area-actual" class="turno-actual-box">
        {{-- Mostrar ventanillas con turnos actuales --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; width: 100%;">
            @forelse($actuales as $ventanilla => $turnos)
                <div style="background: rgba(255, 255, 255, 0.1); border: 2px solid var(--accent); border-radius: 10px; padding: 15px; text-align: center;">
                    <div style="font-size: 1.3rem; font-weight: 700; color: var(--ventColor); margin-bottom: 10px;">{{ $ventanilla }}</div>
                    @if($turnos->first())
                        <div style="font-size: 3rem; font-weight: 800; color: var(--accent); text-shadow: 0px 0px 20px var(--accent);">{{ $turnos->first()->numero }}</div>
                        <div style="font-size: 0.9rem; color: #aaa;">{{ ucfirst($turnos->first()->estado) }}</div>
                    @else
                        <div style="color: #888;">Esperando...</div>
                    @endif
                </div>
            @empty
                <div style="padding: 30px; text-align: center; color: #888;">No hay turnos en atención</div>
            @endforelse
        </div>
    </div>

    {{-- TURNOS EN ESPERA --}}
    <div class="panel">
        <h2><i class="bi bi-hourglass-split"></i> Turnos en Espera</h2>

        <div id="lista-cola" class="subturnos">
            @forelse($cola as $t)
                <div class="subturno">
                    <strong class="tipo {{ $t->tipo }}">{{ $t->numero }}</strong>
                    <span style="color: #aaa;">{{ ucfirst($t->tipo) }}</span>
                </div>
            @empty
                <p class="text-center text-muted">No hay turnos en espera</p>
            @endforelse
        </div>
    </div>

    {{-- TURNOS RECIENTES --}}
    <div class="panel" style="margin-bottom:50px;">
        <h2><i class="bi bi-clock-history"></i> Turnos Recientes</h2>

        <div id="lista-recientes" class="lista-turnos">
            @forelse($recientes as $t)
                <div class="turno-item">
                    <div>
                        <strong>{{ $t->numero }}</strong>
                        <div style="font-size:0.9rem; color:#ddd;">{{ ucfirst($t->tipo) }}</div>
                    </div>
                    <span style="color: var(--accent);">{{ $t->ventanilla ?? '—' }}</span>
                </div>
            @empty
                <p class="text-center text-muted">No hay recientes</p>
            @endforelse
        </div>
    </div>

    {{-- AUTO-REFRESH CADA 2 SEGUNDOS --}}
    <script>
        async function refreshPantalla() {
            try {
                const res = await fetch(window.location.pathname, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) return;

                const data = await res.json();

                // 🔵 ACTUALES
                const area = document.getElementById('area-actual');
                area.innerHTML = '';

                Object.keys(data.actuales).forEach(caja => {
                    const t = data.actuales[caja][0];

                    area.innerHTML += `
                        <div style="
                            background: rgba(255,255,255,.1);
                            border:2px solid var(--accent);
                            border-radius:10px;
                            padding:15px;
                            text-align:center">
                            
                            <div style="font-size:1.3rem;font-weight:700;color:var(--ventColor)">
                                ${caja}
                            </div>

                            <div style="font-size:3rem;font-weight:800;color:var(--accent)">
                                ${t.numero}
                            </div>

                            <div style="font-size:.9rem;color:#aaa">
                                ${t.estado}
                            </div>
                        </div>
                    `;
                });

                // 🟡 COLA
                document.getElementById('lista-cola').innerHTML =
                    data.cola.map(t => `
                        <div class="subturno">
                            <strong class="tipo ${t.tipo}">${t.numero}</strong>
                            <span>${t.tipo}</span>
                        </div>
                    `).join('');

                // 🟢 RECIENTES
                document.getElementById('lista-recientes').innerHTML =
                    data.recientes.map(t => `
                        <div class="turno-item">
                            <strong>${t.numero}</strong>
                            <span>${t.ventanilla ?? '—'}</span>
                        </div>
                    `).join('');
            } catch (e) {
                console.warn('Error refrescando pantalla', e);
            }
        }
        refreshPantalla();
        setInterval(refreshPantalla, 2000);
        </script>
</body>
</html>