<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDBBackup;
use App\Jobs\RunBackupJob;
use App\Models\Log;
use App\Models\Maestro;
use App\Models\User;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Carrera;
use App\Models\Alumno;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class AdminCarreraController extends Controller {
    public function index() {
        // Obtenemos todas las carreras de la BD
        $carreras = Carrera::all();
        // Y Alumnos con el nombre de su carrera y nombre de usuario*
        $alumnos = Alumno::with(['carrera:id,nombre','user:id,name'])->get();
        return view('dashboard.admin.admin', compact('carreras', 'alumnos'));
    }
    
    public function show($id) {
        $carrera = Carrera::findOrFail($id);
        $alumnos = Alumno::with('user:id,name,apellido')->get();
        $maestros = Maestro::with('user:id,name,apellido,email')->get();
        return view('dashboard.admin.admin_carrera', compact('carrera', 'alumnos', 'maestros'));
    }

    public function storeAlumno($carreraId) {
    // 1. Validación corregida (quitamos 'alpha' para permitir números/guiones)
    $data = request()->validate([
        'name'             => 'required|string|max:255',
        'apellido'         => 'required|string|max:255',
        'email'            => 'required|email|unique:users,email',
        'matricula'        => 'required|string|unique:alumnos,matricula',
        'grupo'            => 'required|string',
        'curp'             => 'required|string|max:18',
        'fecha_nacimiento' => 'required|date',
        'edad'             => 'required|integer',
        'sexo'             => 'required',
        'telefono'      => 'required' 
    ]);

    // 2. Crear Usuario
    $usuario = User::create([
        'name'     => $data['name'],
        'apellido' => $data['apellido'],
        'email'    => $data['email'],
        'role'     => 'alumno',
        'foto'     => '',
        'password' => Hash::make('password') // Considera una lógica de password más segura
    ]);

    // 3. Crear Alumno
    $alumno = Alumno::create([
        'user_id'          => $usuario->id,
        'matricula'        => $data['matricula'],
        'carrera_id'       => $carreraId,
        'grupo'            => $data['grupo'],
        'curp'             => $data['curp'],
        'edad'             => $data['edad'],
        'sexo'             => $data['sexo'],
        'fecha_nacimiento' => $data['fecha_nacimiento'],
        'telefono'         => request('telefono') ?? 'Sin teléfono' // Evita el error de nulo
    ]);

    return redirect()->route('admin.show', $carreraId)->with('success', 'Alumno registrado');
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
        $alumno = Alumno::with(['user', 'carrera'])->findOrFail($id);

        return view('dashboard.admin.admin_alumno_expediente', compact('alumno'));
    }

    // Método para mostrar el perfil de un maestro específico desde la perspectiva del Admin
    public function verPerfilMaestro($id)
    {
        $maestro = Maestro::with('user')->findOrFail($id);

        return view('dashboard.admin.admin_maestro_perfil', compact('maestro'));
    }

    public function handleBackup() {
        $user_id = Auth::id();
        Log::registrar('Respaldo de BD', 'El administrador genero un respaldo manual.');
        ProcessDbBackup::dispatch($user_id);
        return redirect()->route("admin.index");
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
}