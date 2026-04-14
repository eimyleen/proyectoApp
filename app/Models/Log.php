<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Log extends Model
{
    protected $fillable = ['user_id', 'accion', 'descripcion', 'ip_address'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Función global para registrar eventos
    public static function registrar($accion, $descripcion = null)
    {
        return self::create([
            'user_id' => Auth::id(),
            'accion' => $accion,
            'descripcion' => $descripcion,
            'ip_address' => request()->ip(),
        ]);
    }
}
