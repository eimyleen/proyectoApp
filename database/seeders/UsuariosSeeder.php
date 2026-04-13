<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Maestro;
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
        $maestroUser = User::create([
            'name' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'maestro@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'maestro',
        ]);

        \App\Models\Maestro::create([
            'user_id' => $maestroUser->id,
            'num_empleado' => 'EMP12345',
            'rfc' => 'PEPJ900101ABC',
            'edad' => 35,
            'sexo' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'telefono' => '3111234567',
            'es_tutor' => true,
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
