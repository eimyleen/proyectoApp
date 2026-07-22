<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionBackupAuto extends Model
{
    //
    protected $fillable = [
        'activo',
        'fecha_inicio',
        'intervalo_minutos',
        'intervalo_horas',
        'intervalo_dias',
        'ultimo_backup'
    ];

    protected $table = 'configuracion_backup_auto';
}
