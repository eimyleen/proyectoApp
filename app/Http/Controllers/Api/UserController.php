<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Listar todos los usuarios.
     */
    public function index()
    {
        // Traemos los usuarios cargando opcionalmente sus relaciones
        $users = User::with(['maestro', 'alumno'])->get();

        return response()->json([
            'status' => true,
            'data' => $users
        ], 200);
    }

    /**
     * POST /api/users
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        // Validamos según la migración y los campos $fillable
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::in(['admin', 'maestro', 'alumno'])],
            'foto'     => 'nullable|string',
        ]);

        $user = User::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $user
        ], 201);
    }

    /**
     * GET /api/users/{id}
     * Mostrar un usuario en específico.
     */
    public function show(string $id)
    {
        $user = User::with(['maestro', 'alumno'])->find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ], 200);
    }

    /**
     * PUT/PATCH /api/users/{id}
     * Actualizar los datos de un usuario.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'apellido' => 'sometimes|string|max:255',
            'email'    => ['sometimes', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role'     => ['sometimes', Rule::in(['admin', 'maestro', 'alumno'])],
            'foto'     => 'nullable|string',
        ]);

        // Si en el request viene contraseña vacía, la eliminamos para no sobrescribir con un valor nulo
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Usuario actualizado correctamente',
            'data' => $user
        ], 200);
    }

    /**
     * DELETE /api/users/{id}
     * Eliminar un usuario.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }
}
