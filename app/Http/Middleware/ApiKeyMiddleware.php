<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtenemos el header que envía la app móvil
        $apiKey = $request->header('X-API-KEY');

        // Comparamos con la clave guardada en el .env
        if ($apiKey !== env('API_SECRET_KEY')) {
            return response()->json([
                'status'  => false,
                'message' => 'Acceso no autorizado. API Key no válida o ausente.'
            ], 401); // 401 Unauthorized
        }

        return $next($request);
    }
}
