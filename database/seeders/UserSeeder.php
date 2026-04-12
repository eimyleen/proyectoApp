<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Maestro;
use App\Models\Expediente;
use App\Models\Carrera;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Carrera prueba
        $carrera = Carrera::firstOrCreate(
            ['siglas' => 'IDGS'], 
            ['nombre' => 'Ingeniería en Desarrollo de Software']
        );

        // Usuario ROOT
        $root = User::create([
            'name' => 'Manuel Root',
            'apellido_paterno' => 'Admin',
            'apellido_materno' => 'Principal',
            'email' => 'root@utnay.edu.mx',
            'password' => Hash::make('password'),
        ]);
        $root->assignRole('root');

        // Usuario ADMIN 
        $admin = User::create([
            'name' => 'Admin',
            'apellido_paterno' => 'Sistema',
            'apellido_materno' => 'UTNay',
            'email' => 'admin@utnay.edu.mx',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        //  Usuario MAESTRO
        $userMaestro = User::create([
            'name' => 'Juan',
            'apellido_paterno' => 'Perez',
            'apellido_materno' => 'Lopez',
            'email' => 'maestro@utnay.edu.mx',
            'password' => Hash::make('password'),
        ]);
        $userMaestro->assignRole('maestro');
        
        Maestro::create([
            'user_id' => $userMaestro->id,
            'num_empleado' => 'EMP001',
            'apellido_paterno' => 'Perez',
            'apellido_materno' => 'Lopez',
            'rfc' => 'PELJ800101XYZ',
            'edad' => 45,
            'sexo' => 'Masculino',
            'fecha_nacimiento' => '1980-01-01',
            'telefono' => '3111234567',
        ]);

        //  Usuario ALUMNO 
        $userAlumno = User::create([
            'name' => 'Estudiante',
            'apellido_paterno' => 'Prueba',
            'apellido_materno' => 'UT',
            'email' => 'alumno@utnay.edu.mx',
            'password' => Hash::make('password'),
        ]);
        $userAlumno->assignRole('alumno');

        Expediente::create([
            'user_id' => $userAlumno->id,
            'carrera_id' => $carrera->id,
            'matricula' => '20240001',
            'apellido_paterno' => 'Prueba',
            'apellido_materno' => 'UT',
            'curp' => 'ABC123456HDFRRR01',
            'edad' => 20,
            'sexo' => 'Femenino',
            'fecha_nacimiento' => '2004-05-20',
            'telefono' => '3119876543',
        ]);
    }
}
