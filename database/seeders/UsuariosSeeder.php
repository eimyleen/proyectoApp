<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'name' => 'Admin',
            'apellido' => 'Sistema',
            'email' => 'admin@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Usuario Maestro
        User::create([
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'maestro@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'maestro',
        ]);

        // Usuario Alumno
        User::create([
            'name' => 'Luis',
            'apellido' => 'García',
            'email' => 'alumno@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'alumno',
        ]);
    }
}
