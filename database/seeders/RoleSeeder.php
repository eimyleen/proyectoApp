<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // -- Creación de Roles --
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $maestro = Role::firstOrCreate(['name' => 'maestro']);
        $alumno = Role::firstOrCreate(['name' => 'alumno']);

        $permisos = [
            'carreras.ver'               => [$admin],
            'carreras.crear'             => [$admin],
            'carreras.editar'            => [$admin],
            'carreras.eliminar'          => [$admin],
            
            'administradores.ver'        => [$admin],
            'administradores.crear'      => [$admin],
            
            'maestros.ver'               => [$admin],
            'maestros.crear'             => [$admin],
            
            'alumnos.ver'                => [$admin],
            'alumnos.crear'              => [$admin],
            
            'grupos.ver'                 => [$admin],
            'grupos.asignar-tutores'     => [$admin],
            
            'expedientes.ver'            => [$admin, $maestro],
            'expedientes.crear'          => [$admin, $maestro],
            
            'grupos.ver-asignados'       => [$maestro],
            'calificaciones.gestionar'   => [$maestro],
            
            'expediente.ver-propio'      => [$alumno],
            'calificaciones.ver-propio'  => [$alumno],
        ];

        foreach ($permisos as $nombrePermiso => $roles) {
            $permiso = Permission::firstOrCreate(['name' => $nombrePermiso]);
            $permiso->syncRoles($roles);
        }
    }
}
