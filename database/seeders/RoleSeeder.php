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
        $root = Role::create(['name' => 'root']);
        $admin = Role::create(['name' => 'admin']);
        $maestro = Role::create(['name' => 'maestro']);
        $alumno = Role::create(['name' => 'alumno']);

        // --- Permisos de CRUD en Carreras ---

        Permission::create(['name' => 'carreras.ver'])->syncRoles([$root]);
        Permission::create(['name' => 'carreras.crear'])->syncRoles($root);
        Permission::create(['name' => 'carreras.editar'])->syncRoles($root);
        Permission::create(['name' => 'carreras.eliminar'])->syncRoles($root);

        // --- Permisos de CRUD en Usuarios ---

        Permission::create(['name' => 'administradores.ver'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'administradores.crear'])->syncRoles([$root]);
        Permission::create(['name' => 'administradores.editar'])->syncRoles([$root]);
        Permission::create(['name' => 'administradores.eliminar'])->syncRoles([$root]);

        Permission::create(['name' => 'maestros.ver'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'maestros.crear'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'maestros.editar'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'maestros.eliminar'])->syncRoles([$root, $admin]);

        Permission::create(['name' => 'alumnos.ver'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'alumnos.crear'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'alumnos.editar'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'alumnos.eliminar'])->syncRoles([$root, $admin]);

        // --- Permisos de CRUD en Grupos ---

        Permission::create(['name' => 'grupos.ver'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.crear'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.editar'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.eliminar'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.asignar-tutores'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.asignar-maestros'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'grupos.asignar-alumnos'])->syncRoles([$root, $admin]);

        // --- Permisos de Tutor (Maestro con permisos de tutor) ---

        Permission::create(['name' => 'expedientes.ver'])->syncRoles([$root, $admin, $maestro]);
        Permission::create(['name' => 'expedientes.crear'])->syncRoles([$root, $admin, $maestro]);

        // --- Permisos de Maestro ---

        Permission::create(['name' => 'grupos.ver-asignados'])->syncRoles($maestro);
        Permission::create(['name' => 'calificaciones.gestionar'])->syncRoles($maestro);

        // --- Permisos de Alumno ---

        Permission::create(['name' => 'expediente.ver-propio'])->syncRoles([$alumno]);
        Permission::create(['name' => 'calificaciones.ver-propio'])->syncRoles([$alumno]);
    }
}
