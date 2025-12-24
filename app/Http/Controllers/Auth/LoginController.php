<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
         // 1️⃣ VALIDACIONES BÁSICAS
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5'
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debes ingresar un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 5 caracteres.'
        ]);

        // 2️⃣ VERIFICAR SI EL USUARIO EXISTE
        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo no está registrado.'])->withInput();
        }

        // 3️⃣ VERIFICAR CONTRASEÑA
        if (!Hash::check($request->password, $usuario->password)) {
            return back()->withErrors(['password' => 'La contraseña es incorrecta.'])->withInput();
        }

        // 4️⃣ INICIAR SESIÓN
        Auth::login($usuario);

        // 5️⃣ REDIRECCIONAR SEGÚN ROL
        if ($usuario->rol === 'administrador') {
            return redirect()->route('admin.dashboard');
        }

        if ($usuario->rol === 'operador') {
            return redirect()->route('operador.panel');
        }

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario) {
            // Nota: ya no cerramos la asignación al cerrar sesión.
            // Las asignaciones permanecerán 'abiertas' hasta que se cierren manualmente
            // desde el admin o mediante la operación específica de cierre.
        }

        // Cerrar sesión
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Sesión cerrada correctamente.');
    }
}
