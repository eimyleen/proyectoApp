<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Log;

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

            // ... después de que el login es exitoso:
            Log::create([
                'user_id' => Auth::id(),
                'accion' => 'Inicio de sesión',
                'descripcion' => 'El usuario ha accedido al sistema',
                'ip_address' => request()->ip()
            ]);

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

    public function updateFoto(Request $request)
    {
        // Validar que el archivo sea una imagen válida y no exceda los 2MB
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Buscamos al usuario usando el Modelo User 
        $user = User::find(Auth::id()); 

        if ($request->hasFile('foto')) {
            
            // Borrar la foto anterior si existe
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }

            // Guarda la nueva imagen
            // Se almacena en: storage/app/public/perfiles/ 
            $path = $request->file('foto')->store('perfiles', 'public');

            // Actualiza la ruta en la base de datos
            $user->update([
                'foto' => $path
            ]);
        }

        return back()->with('success', 'Foto actualizada correctamente.');
    }
}
