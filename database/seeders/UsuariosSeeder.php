<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Maestro;
use App\Models\Carrera;
use App\Models\Alumno;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Horario;
use App\Models\Calificacion;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ---------- Usuarios de prueba ----------

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

        // Usuario Alumno
        $userAlumno = User::create([
            'name' => 'Luis',
            'apellido' => 'García',
            'email' => 'alumno@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'alumno',
        ]);

        $userAlumno2 = User::create([
            'name' => 'Pepe',
            'apellido' => 'Toño',
            'email' => 'alumnotono@utnay.edu.mx',
            'password' => Hash::make('password123'),
            'role' => 'alumno',
        ]);

        // ---------- Carreras de prueba ----------

        $carrera = Carrera::create([
            'nombre' => 'Ingeniería en Tecnologías de la Información e Innovación Digital',
            'clave' => 'ITIID',
            'logo' => 'img/carreras/ing_tec_info.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería en Alimentos',
            'clave' => 'IAL',
            'logo' => 'img/carreras/ing_alimentos.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería Civil',
            'clave' => 'IC',
            'logo' => 'img/carreras/ing_civil.png',
        ]);

        Carrera::create([
            'nombre' => 'T.S.U. en Inteligencia Artificial',
            'clave' => 'IA',
            'logo' => 'img/carreras/ing_inte_artificial.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería en Logística Internacional',
            'clave' => 'ILI',
            'logo' => 'img/carreras/ing_logistica.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería en Mantenimiento Industrial',
            'clave' => 'IMI',
            'logo' => 'img/carreras/ing_mant_industrial.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería en Mecatrónica',
            'clave' => 'IMT',
            'logo' => 'img/carreras/ing_mecatronica.png',
        ]);

        Carrera::create([
            'nombre' => 'Ingeniería en Microelectrónica y Semiconductores',
            'clave' => 'IMS',
            'logo' => 'img/carreras/ing_micro_semic.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Administración',
            'clave' => 'LAD',
            'logo' => 'img/carreras/lic_admin.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Gastronomía',
            'clave' => 'LGT',
            'logo' => 'img/carreras/lic_gastro.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Negocios y Mercadotecnia',
            'clave' => 'LNM',
            'logo' => 'img/carreras/lic_merca.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Psicología',
            'clave' => 'LPS',
            'logo' => 'img/carreras/lic_psicologia.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Seguridad Pública',
            'clave' => 'LSP',
            'logo' => 'img/carreras/lic_seg_publ.png',
        ]);

        Carrera::create([
            'nombre' => 'Licenciatura en Gestión y Desarrollo Turístico',
            'clave' => 'LGDT',
            'logo' => 'img/carreras/lic_turismo.png',
        ]);

        // ---------- Datos de prueba para grupos ----------

        $grupo = Grupo::create([
            'nombre' => 'TI-92',
            'grado' => 'Noveno',
            'carrera_id' => $carrera->id,
        ]);

        // ---------- Datos de prueba para los usuarios ----------

        // Datos de prueba para el maestro
        $maestro = Maestro::create([
            'user_id' => $maestroUser->id,
            'num_empleado' => 'EMP12345',
            'rfc' => 'PEPJ900101ABC',
            'sexo' => 'M',
            'fecha_nacimiento' => '1990-01-01',
            'telefono' => '3111234567',
            'es_tutor' => true,
        ]);

        // Datos de prueba para el alumno
        $alumnoModelo = Alumno::create([
            'user_id' => $userAlumno->id,
            'matricula' => 'TIC-310000',
            'curp' => 'GARL000101HNTNNN01',
            'sexo' => 'M',
            'fecha_nacimiento' => '2005-01-01',
            'telefono' => '3111234567',
            'carrera_id' => $carrera->id,
        ]);

        $alumnoModelo2 = Alumno::create([
            'user_id' => $userAlumno2->id,
            'matricula' => 'TIC-310054',
            'curp' => 'FHJDBIVDIJ98380798',
            'sexo' => 'M',
            'fecha_nacimiento' => '2004-01-01',
            'telefono' => '3111234527',
            'carrera_id' => $carrera->id,
        ]);

        // ---------- Materias de prueba para la carrera ITIID ----------

        $m1 = Materia::create(['nombre' => 'Base de Datos para Aplicaciones', 'carrera_id' => $carrera->id ]);
        $m2 = Materia::create(['nombre' => 'Desarrollo Web Profesional', 'carrera_id' => $carrera->id]);
        $m3 = Materia::create(['nombre' => 'Aplicaciones Móviles Multiplataforma', 'carrera_id' => $carrera->id]);
        $m4 = Materia::create(['nombre' => 'Seguridad Informática', 'carrera_id' => $carrera->id]);
        $m5 = Materia::create(['nombre' => 'Inglés IX', 'carrera_id' => $carrera->id]);

        // ---------- Horarios para el grupo TI-41 ----------

        $horarios = [
            // Lunes
            ['grupo_id' => $grupo->id, 'dia' => 'Lunes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'materia_id' => $m1->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],
            ['grupo_id' => $grupo->id, 'dia' => 'Lunes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'materia_id' => $m2->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 10'],
            ['grupo_id' => $grupo->id, 'dia' => 'Lunes', 'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00', 'materia_id' => $m3->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],

            // Martes
            ['grupo_id' => $grupo->id, 'dia' => 'Martes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'materia_id' => $m4->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 5'],
            ['grupo_id' => $grupo->id, 'dia' => 'Martes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'materia_id' => $m5->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 8'],
            ['grupo_id' => $grupo->id, 'dia' => 'Martes', 'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00', 'materia_id' => $m1->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],

            // Miércoles
            ['grupo_id' => $grupo->id, 'dia' => 'Miércoles', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'materia_id' => $m2->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 10'],
            ['grupo_id' => $grupo->id, 'dia' => 'Miércoles', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'materia_id' => $m3->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],
            ['grupo_id' => $grupo->id, 'dia' => 'Miércoles', 'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00', 'materia_id' => $m4->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 5'],

            // Jueves
            ['grupo_id' => $grupo->id, 'dia' => 'Jueves', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'materia_id' => $m5->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 8'],
            ['grupo_id' => $grupo->id, 'dia' => 'Jueves', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'materia_id' => $m1->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],
            ['grupo_id' => $grupo->id, 'dia' => 'Jueves', 'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00', 'materia_id' => $m2->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 10'],

            // Viernes
            ['grupo_id' => $grupo->id, 'dia' => 'Viernes', 'hora_inicio' => '07:00:00', 'hora_fin' => '09:00:00', 'materia_id' => $m3->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 1'],
            ['grupo_id' => $grupo->id, 'dia' => 'Viernes', 'hora_inicio' => '09:00:00', 'hora_fin' => '11:00:00', 'materia_id' => $m4->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 5'],
            ['grupo_id' => $grupo->id, 'dia' => 'Viernes', 'hora_inicio' => '11:00:00', 'hora_fin' => '13:00:00', 'materia_id' => $m5->id, 'maestro_id' => $maestro->id, 'aula' => 'Lab 8'],
        ];

        foreach ($horarios as $h) {
            Horario::create($h);
        }

        // ---------- Calificaciones de prueba para el alumno ----------

        $calificaciones = [
            [
                'alumno_id' => $alumnoModelo->id, 
                'materia_id' => $m1->id,
                'periodo' => 'Enero - Abril 2026',
                'calificacion' => 9.5,
            ],
            [
                'alumno_id' => $alumnoModelo->id,
                'materia_id' => $m2->id,
                'periodo' => 'Enero - Abril 2026',
                'calificacion' => 10.0,
            ],
            [
                'alumno_id' => $alumnoModelo->id,
                'materia_id' => $m3->id,
                'periodo' => 'Enero - Abril 2026',
                'calificacion' => 8.8,
            ],
            [
                'alumno_id' => $alumnoModelo->id,
                'materia_id' => $m4->id,
                'periodo' => 'Enero - Abril 2026',
                'calificacion' => 9.2,
            ],
            [
                'alumno_id' => $alumnoModelo->id,
                'materia_id' => $m5->id,
                'periodo' => 'Enero - Abril 2026',
                'calificacion' => 9.7,
            ],
        ];

        foreach ($calificaciones as $c) {
            Calificacion::create($c);
        }

        // ---------- Cosas de gabriel ----------

        $maestro->carreras()->attach([$maestro->id]);

        $grupo->alumnos()->attach([$alumnoModelo->id], ['periodo' => 'Noveno']);
    }
}
