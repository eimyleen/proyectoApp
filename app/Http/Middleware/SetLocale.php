<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
   public function handle(Request $request, Closure $next)
{
    // Si la sesión tiene un idioma, lo usa; si no, usa el del config (que ahora es 'es')
    if (Session::has('locale')) {
        App::setLocale(Session::get('locale'));
    } else {
        App::setLocale(config('app.locale'));
    }

    return $next($request);
}
}