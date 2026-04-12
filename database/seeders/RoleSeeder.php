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
        $root = Role::firstOrCreate(['name' => 'root']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $maestro = Role::firstOrCreate(['name' => 'maestro']);
        $alumno = Role::firstOrCreate(['name' => 'alumno']);

        $permisos = [
            'carreras.ver'               => [$root],
            'carreras.crear'             => [$root],
            'carreras.editar'            => [$root],
            'carreras.eliminar'          => [$root],
            
            'administradores.ver'        => [$root, $admin],
            'administradores.crear'      => [$root],
            
            'maestros.ver'               => [$root, $admin],
            'maestros.crear'             => [$root, $admin],
            
            'alumnos.ver'                => [$root, $admin],
            'alumnos.crear'              => [$root, $admin],
            
            'grupos.ver'                 => [$root, $admin],
            'grupos.asignar-tutores'     => [$root, $admin],
            
            'expedientes.ver'            => [$root, $admin, $maestro],
            'expedientes.crear'          => [$root, $admin, $maestro],
            
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
