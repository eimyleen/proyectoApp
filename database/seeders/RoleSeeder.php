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
        // 1. Crear Roles
        $root = Role::create(['name' => 'root']);
        $admin = Role::create(['name' => 'admin']);
        $maestro = Role::create(['name' => 'maestro']);
        $alumno = Role::create(['name' => 'alumno']);

        // 1. Permisos solo root
        // Solo root puede gestionar carreras
        Permission::create(['name' => 'gestionar carreras'])->assignRole($root);

        // 2. Permisos de Usuarios (Root y Admin)
        // En el controlador se define que el Admin:
        // Solo puede gestionar a maestros y alumnos, no a otros admins ni al root
        Permission::create(['name' => 'crear usuarios'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'editar usuarios'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'eliminar usuarios'])->syncRoles([$root, $admin]);

        // 3. Permisos de Grupos y Asignación (Root y Admin)
        Permission::create(['name' => 'gestionar grupos'])->syncRoles([$root, $admin]);
        Permission::create(['name' => 'asignar tutor'])->syncRoles([$root, $admin]);

        // 4. Permisos de Maestro
        Permission::create(['name' => 'ver grupos asignados'])->assignRole($maestro);
        Permission::create(['name' => 'gestionar tutorados'])->assignRole($maestro);
    }
}
