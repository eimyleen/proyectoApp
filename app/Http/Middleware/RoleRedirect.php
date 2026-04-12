<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
            
            // Si la URL actual coincide con el rol del usuario 
            if (($user->hasRole(['root', 'admin']) && $request->is('dashboard/admin*')) ||
                ($user->hasRole('maestro') && $request->is('dashboard/maestro*')) ||
                ($user->hasRole('alumno') && $request->is('dashboard/alumno*'))) {
                return $next($request);
            }

            // Redirección basada en el primer rol encontrado
            if ($user->hasRole('root') || $user->hasRole('admin')) {
                return redirect()->intended('/dashboard/admin');
            } elseif ($user->hasRole('maestro')) {
                return redirect()->intended('/dashboard/maestro');
            } elseif ($user->hasRole('alumno')) {
                return redirect()->intended('/dashboard/alumno');
            }
        }

        return $next($request);
    
    }
}
