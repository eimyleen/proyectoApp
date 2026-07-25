<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAlumnoRequest;
use App\Http\Requests\CreateMaestroRequest;
use App\Models\ConfiguracionBackupAuto;
use App\Models\Log;
use App\Models\Maestro;
use App\Models\Grupo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminCarreraController extends Controller {
    public function index() {
        // Obtenemos todas las carreras de la BD
        $carreras = Carrera::all();
        // Y Alumnos con el nombre de su carrera y nombre de usuario*
        $alumnos = Alumno::with(['carrera:id,nombre','user:id,name'])->get();
        $configBackAuto = ConfiguracionBackupAuto::first();
        return view('dashboard.admin.admin', compact('carreras', 'alumnos', 'configBackAuto'));
    }
    
    public function show($id, Request $req) {
        $carrera = Carrera::findOrFail($id);
        $grupos = Grupo::with('maestro.user')->where('carrera_id', $id)->get();
        $grupoId = $req->grupo_id;

        $alumnos = Alumno::with(['user:id,name,apellido', 'grupos.carrera'])
        ->whereHas('grupos', function ($q) use ($id, $grupoId) {
            $q->where('carrera_id', $id);

            if ($grupoId) {
                $q->where('grupos.id', $grupoId);
            }
        })->get();

        $maestros = Maestro::with('user:id,name,apellido,email')->whereHas('carreras', function($d) use ($id) {
            $d->where('carrera_id', $id);
        })->get();

        $totalAlumnosGrupo = $grupoId
            ? $alumnos->count()
            : Alumno::whereHas('grupos', fn($q) => $q->where('carrera_id', $id))->count();

        $grupoSeleccionado = $grupoId ? $grupos->firstWhere('id', $grupoId) : null;

        return view('dashboard.admin.admin_carrera', compact('carrera', 'alumnos', 'maestros', 'grupos', 'grupoSeleccionado', 'totalAlumnosGrupo', 'grupoId'));
    }

    public function storeAlumno($carreraId, CreateAlumnoRequest $req) {
        // 1. Obtener los datos del formulario validados
        $data = $req->validated();

        // 2. Crear Usuario
        $usuario = User::create([
            'name'     => $data['name'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'role'     => 'alumno',
            'password' => Hash::make('password') // Considera una lógica de password más segura
        ]);

        //3. Crear el Alumno
        $alumno = Alumno::create([
            'matricula' => $data['matricula'],
            'curp' => $data['curp'],
            'sexo' => $data['sexo'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'telefono' => $data['telefono'],
            'user_id' => $usuario->id,
            'carrera_id' => $carreraId
        ]);

        //4. Anclarlo al grupo asignado
        $alumno->grupos()->attachOrFail($data['grupo'], ['periodo' => 'Primero']);

        return redirect() -> route('admin.show', $carreraId) -> with('success', 'Alumno creado');
    }

    public function storeMaestro($carreraId, CreateMaestroRequest $req) {
        // 1. Obtener los datos del formulario validados
        $data = $req->validated();

        // 2. Crear Usuario
        $usuario = User::create([
            'name'     => $data['name'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'role'     => 'maestro',
            'password' => Hash::make('password') // Considera una lógica de password más segura
        ]);

        //3. Crear el Maestro
        $maestro = Maestro::create([
            'num_empleado' => $data['num_empleado'],
            'rfc' => $data['rfc'],
            'sexo' => $data['sexo'], 
            'fecha_nacimiento' => $data['fecha_nacimiento'], 
            'telefono' => $data['telefono'], 
            'es_tutor' => 0,
            'user_id' => $usuario->id
        ]);

        //4. Anclarlo a la carrera perteneciente
        $maestro->carreras()->attachOrFail($carreraId);

        return redirect() -> route('admin.show', $carreraId) -> with('success', 'Maestro creado');
    }

    public function update(Request $request, string $id) {
        $carrera = Carrera::findOrFail($id);
        $carrera -> nombre = $request -> inNombre;
        $carrera -> clave = $request -> inClave;
        $carrera -> logo = $request -> inLogo;
        $carrera -> save();
        return redirect() -> route('admin.show', $carrera);
    }

    public function store(Request $request) {
        request()->validate(
            [
                'nombre'=>'required',
                'clave'=>'required|alpha'
            ]
        );
        Carrera::create([
            //BD ATRIBB NAME - INPUT NAME
            'nombre'=>request('nombre'),
            'clave'=>request('clave'),
            'logo'=>request('logo')
        ]);
        return redirect()->route('admin.index');
    }

    public function delete(string $id) {
        $Item = Carrera::findOrFail($id);
        $Item -> delete();
        return redirect() -> route("admin.index");
    }

    // Método para mostrar el expediente de un alumno específico desde la perspectiva del Admin
    public function verExpediente($id)
    {
        $alumno = Alumno::findOrFail($id);
        $grupo = $alumno->grupos->first();
        $carrera = $grupo?->carrera;

        return view('dashboard.admin.admin_alumno_expediente', compact('alumno', 'grupo', 'carrera'));
    }

    // Método para mostrar el perfil de un maestro específico desde la perspectiva del Admin
    public function verPerfilMaestro($id)
    {
        $maestro = Maestro::with('user')->findOrFail($id);

        return view('dashboard.admin.admin_maestro_perfil', compact('maestro'));
    }

     public function manejarBackupManual()
    {
        DB::beginTransaction();
        try {
            Log::registrar('Respaldo de BD', 'El administrador genero un respaldo manual.');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $db = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        $fileName = 'backup_manual_' . now()->format('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $fileName);

        // Crear carpeta si no existe
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Comando mysqldump
        $command = sprintf(
            'mysqldump --skip-ssl --user=%s --password=%s --host=%s %s > %s 2>&1',
            escapeshellarg($user),
            escapeshellarg($pass),
            escapeshellarg($host),
            escapeshellarg($db),
            escapeshellarg($path)
        );

        exec($command, $output, $result);

        if ($result !== 0) {
            return back()->with('error', 'Error al generar backup');
        }

        return back()->with('success', 'Backup generado correctamente');
    }

    public function guardarConfiguracionBackupAuto()
    {
        $data = request()->validate([
            'fecha_inicio' => 'required|date',
            'minutos'      => 'required|integer|min:0|max:59',
            'horas'        => 'required|integer|min:0|max:23',
            'dias'         => 'required|integer|min:0|max:31',
        ]);

        ConfiguracionBackupAuto::updateOrCreate(['id' => 1], [
            'activo' => true,
            'fecha_inicio' => $data['fecha_inicio'],
            'intervalo_minutos' => $data['minutos'],
            'intervalo_horas' => $data['horas'],
            'intervalo_dias' => $data['dias']
        ]);

        return back();
    }

    public function quitarConfiguracionBackupAuto() 
    {
        $back = ConfiguracionBackupAuto::first();

        if($back !== null) {
            $back::updateOrCreate(
                ['id' => 1],
                ['activo' => false, 'ultimo_backup' => null]
            );
        }
        return back();
    }

    public function descargarAlumnosPDF()
    {
        // Obtenemos todos los alumnos
        $alumnos = Alumno::with('user', 'carrera')->get();

        Log::registrar('Descarga PDF', 'El administrador descargó la lista global de alumnos');

        // Reutilizamos la vista de PDF que ya creamos
        $pdf = Pdf::loadView('pdf.lista_alumnos_maestro', compact('alumnos'));

        return $pdf->download('reporte_global_alumnos_' . now()->format('d-m-Y') . '.pdf');
    }

    public function updateAlumno(Request $request, $carreraId, $alumnoId)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'matricula'        => 'required|string|max:12',
            'curp'             => 'required|string|max:18',
            'sexo'             => 'required|in:M,F,Otro',
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'nullable|string|max:20',
            'grupo'            => 'required|exists:grupos,id',
        ]);

        $alumno = Alumno::findOrFail($alumnoId);
        $alumno->user->update([
            'name'     => $data['name'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
        ]);

        $alumno->update([
            'matricula'        => $data['matricula'],
            'curp'             => $data['curp'],
            'sexo'             => $data['sexo'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'telefono'         => $data['telefono'],
        ]);

        $alumno->grupos()->sync([$data['grupo'] => ['periodo' => 'Primero']]);

        return redirect()->route('admin.show', $carreraId)->with('success', 'Alumno actualizado correctamente');
    }

    public function updateMaestro(Request $request, $carreraId, $maestroId)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'apellido'         => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'num_empleado'     => 'required|string|max:20',
            'rfc'              => 'required|string|max:13',
            'sexo'             => 'required|in:M,F,Otro',
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'nullable|string|max:20',
        ]);

        $maestro = Maestro::findOrFail($maestroId);
        $maestro->user->update([
            'name'     => $data['name'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
        ]);

        $maestro->update([
            'num_empleado'     => $data['num_empleado'],
            'rfc'              => $data['rfc'],
            'sexo'             => $data['sexo'],
            'fecha_nacimiento' => $data['fecha_nacimiento'],
            'telefono'         => $data['telefono'],
        ]);

        return redirect()->route('admin.show', $carreraId)->with('success', 'Maestro actualizado correctamente');
    }

    public function updateGrupo(Request $request, $carreraId, $grupoId)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:10',
            'grado'     => 'required|in:1,2,3,4,5,6,7,8,9,10,11',
            'maestro_id' => 'nullable|exists:maestros,id',
        ]);

        $grupo = Grupo::findOrFail($grupoId);
        $grupo->update([
            'nombre'     => $data['nombre'],
            'grado'      => $data['grado'],
            'maestro_id' => $data['maestro_id'] ?: null,
        ]);

        return redirect()->route('admin.show', $carreraId)->with('success', 'Grupo actualizado correctamente');
    }

    public function storeGrupo(Request $request, $carreraId)
    {
        $request->validate([
            'nombre' => 'required|string|max:10',
            'grado'  => 'required|in:1,2,3,4,5,6,7,8,9,10,11',
        ]);

        Grupo::create([
            'nombre'     => $request->nombre,
            'grado'      => $request->grado,
            'carrera_id' => $carreraId,
        ]);

        return redirect()->route('admin.show', $carreraId)->with('success', 'Grupo creado correctamente');
    }

    public function deleteAlumno($carreraId, $alumnoId)
    {
        $alumno = Alumno::findOrFail($alumnoId);
        $alumno->grupos()->detach();
        $alumno->user()->delete();
        $alumno->delete();

        return redirect()->route('admin.show', $carreraId)->with('success', 'Alumno eliminado correctamente');
    }

    public function deleteMaestro($carreraId, $maestroId)
    {
        $maestro = Maestro::findOrFail($maestroId);
        $maestro->carreras()->detach();
        Grupo::where('maestro_id', $maestroId)->update(['maestro_id' => null]);
        $maestro->user()->delete();
        $maestro->delete();

        return redirect()->route('admin.show', $carreraId)->with('success', 'Maestro eliminado correctamente');
    }

    public function deleteGrupo($carreraId, $grupoId)
    {
        $grupo = Grupo::findOrFail($grupoId);
        $grupo->alumnos()->detach();
        $grupo->delete();

        return redirect()->route('admin.show', $carreraId)->with('success', 'Grupo eliminado correctamente');
    }
}