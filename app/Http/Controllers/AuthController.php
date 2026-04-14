<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra la vista del login
    public function showLogin()
    {
        return view('login'); 
    }

    // Procesa el intento de login
    public function login(Request $request)
    {
        // Se validan los datos (usamos 'correo')
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Se autentica
        // Mapeamos 'correo' del form a 'email' de la DB
        if (Auth::attempt(['email' => $credentials['correo'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $user = Auth::user();

            // Redirección basada en el rol
            if ($user->role === 'admin') {
                return redirect()->route('admin.index');
            } elseif ($user->role === 'maestro') {
                return redirect()->route('maestro.index');
            }elseif ($user->role === 'alumno') {
                return redirect()->route('alumno.index');
            }

            // Por defecto
            return redirect()->intended('login');
        }

        // Si falla, regresar con error
        return back()->withErrors([
            'correo' => 'Las credenciales no coinciden.',
        ])->onlyInput('correo');
    }

    // Cierra la sesión del usuario
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
