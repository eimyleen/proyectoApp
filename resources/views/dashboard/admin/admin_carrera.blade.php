{{-- 
    ============================================================
    ADMIN - DETALLE DE CARRERA
    ============================================================
    Esta vista muestra el detalle de una carrera específica.
    Muestra:
    - Header con logo, nombre, clave y acciones (editar/eliminar)
    - Pestañas para cambiar entre Grupos y Maestros
    - Tabla de alumnos con filtro por grupo
    - Tabla de maestros
    - Modales para agregar alumno, agregar maestro y editar carrera
    
    RELACIÓN CON OTRAS VISTAS:
    - Extiende el layout: layouts.dashboard
    - Usa los estilos de: dashboard_admin.css
    - Botón de regreso: visible (back-button)
    - Se conecta con: admin.index (dashboard de admin)
    ============================================================ 
--}}

@extends('layouts.dashboard')

{{-- 
    TÍTULOS DE LA PÁGINA
    El primero usa traducción (__()), el segundo es texto fijo.
    El que prevalece es el último definido.
--}}
@section('title', __('messages.career_detail_title'))
@section('subtitle', __('messages.career_detail_subtitle'))

@section('title', 'Administrador - Detalle de Carrera')
@section('subtitle', 'Gestiona los grupos y maestros de esta carrera')

{{-- 
    BOTÓN DE REGRESO
    Esta sección hace visible el botón de regreso en el header.
--}}
@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

{{-- 
    URL DE REGRESO
    Define a dónde redirige el botón de regreso.
    En este caso, al dashboard de administrador.
--}}
@section('back-url', '/dashboard/admin')

{{-- CSS ADICIONAL --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

{{-- CONTENIDO PRINCIPAL --}}
@section('content')
    
    {{-- 
        CONTENEDOR PRINCIPAL
        Fondo blanco con sombra y bordes redondeados.
    --}}
    <div class="carrera-container">
        
        {{-- ======================================================
             HEADER DE LA CARRERA
             ====================================================== 
             Muestra:
             - Logo circular de la carrera (o logo por defecto)
             - Nombre de la carrera
             - Clave de la carrera
             - Botones: Editar carrera y Eliminar carrera
        --}}
        <div class="carrera-header">
            
            {{-- Logo de la carrera --}}
            <div class="carrera-logo">
                <div class="logo-circular" style="width: 120px; height: 120px; overflow: hidden;">
                    @if($carrera->logo)
                        <img src="{{ asset($carrera->logo) }}" 
                            alt="{{ $carrera->nombre }}"
                            style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <img src="{{ asset('img/jaguar.png') }}" alt="{{ __('messages.admin_no_logo') }}">
                    @endif
                </div>
            </div>
            
            {{-- Información de la carrera --}}
            <div class="carrera-info">
                <h2>{{ $carrera->nombre }}</h2>
                <p class="carrera-clave">{{ __('messages.groups_id_card') . ': ' . $carrera->clave }}</p>
                <p>{{ __('messages.career_management') }}</p>
            </div>
            
            {{-- Acciones de la carrera --}}
            <div class="carrera-acciones">
                {{-- Botón Editar (abre modal) --}}
                <button class="btn-editar" id="btnEditarCarrera">
                    <img src="{{ asset('img/editar.png') }}" alt="Editar" class="btn-icono"> 
                    {{ __('messages.btn_edit_career') }}
                </button>
                
                {{-- 
                    ======================================================
                    NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE ELIMINAR CARRERA
                    ======================================================
                    Se agregó ID "formEliminarCarrera" al formulario y una
                    alerta de confirmación con SweetAlert antes de ejecutar
                    la acción.
                    
                    La ruta y el método de eliminación se mantienen igual.
                    ======================================================
                --}}
                <form action="{{ route('admin.delete', $carrera) }}" method="POST" id="formEliminarCarrera">
                    @csrf 
                    @method('DELETE')
                    <button type="submit" class="btn-eliminar-carrera" id="btnEliminarCarrera">
                        ✕ {{ __('messages.btn_delete_career') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- ======================================================
             PESTAÑAS
             ====================================================== 
             Permite cambiar entre la vista de Grupos y Maestros.
             La pestaña activa tiene un borde inferior azul oscuro.
        --}}
        <div class="tabs">
            <button class="tab-btn active" data-tab="grupos">{{ __('messages.tab_groups') }}</button>
            <button class="tab-btn" data-tab="maestros">{{ __('messages.tab_teachers') }}</button>
        </div>

        {{-- ======================================================
             CONTENIDO - GRUPOS
             ====================================================== 
             Muestra:
             - Panel del tutor (información del tutor del grupo)
             - Filtro para seleccionar un grupo específico
             - Botones: Agregar alumno y Descargar lista
             - Tabla con los alumnos del grupo seleccionado
        --}}
        <div class="tab-content active" id="tab-grupos">
            
            {{-- Panel de información del tutor --}}
            <div class="tutor-info-panel">
                <div class="tutor-info">
                    <span class="tutor-label">{{ __('messages.tutor_label') }}:</span>
                    <span class="tutor-nombre" id="tutorNombre">
                        @if($grupoSeleccionado && $grupoSeleccionado->maestro)
                            {{ $grupoSeleccionado->maestro->user?->name }} {{ $grupoSeleccionado->maestro->user?->apellido }}
                        @else
                            {{ __('messages.groups_no_tutor') }}
                        @endif
                    </span>
                </div>
            </div>

            {{-- Filtro y acciones --}}
            <div class="filtro-grupo">
                {{-- Select de grupos (recarga la página al cambiar) --}}
                <form method="GET">
                    <select name="grupo_id" class="grupo-select" onchange="this.form.submit()">
                        <option value="">{{ __('messages.select_group') }}</option>
                        @foreach ($grupos as $grupo)
                            <option value="{{ $grupo->id }}"
                                {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>

                {{-- Contador de alumnos --}}
                <span class="contador-alumnos">
                    {{ $totalAlumnosGrupo }} {{ $totalAlumnosGrupo === 1 ? 'alumno' : 'alumnos' }}
                    @if($grupoSeleccionado)
                        en grupo {{ $grupoSeleccionado->nombre }}
                    @endif
                </span>

                {{-- Botones de acción --}}
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarGrupo">
                        {{ '+ ' . __('messages.btn_add_group') }}
                    </button>
                    <button class="btn-agregar" id="btnEditarGrupo" style="background: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; padding: 0.5rem 0.875rem; font-size: 0.8rem;">
                        <img src="{{ asset('img/editar.png') }}" alt="Editar" style="width: 0.875rem; height: 0.875rem;"> {{ __('messages.btn_edit_group') }}
                    </button>
                    <button class="btn-agregar" id="btnEliminarGrupo" style="background: #ffffff; color: #ef4444; border: 1px solid #fecaca; padding: 0.5rem 0.875rem; font-size: 0.8rem;">
                        <img src="{{ asset('img/borrar.svg') }}" alt="Eliminar" style="width: 0.875rem; height: 0.875rem;"> {{ __('messages.btn_delete_group') }}
                    </button>
                    <button class="btn-agregar" id="btnAgregarAlumno">
                        {{ __('messages.btn_add_student') }}
                    </button>
                    {{-- 
                        ======================================================
                        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE DESCARGA
                        ======================================================
                        Se agregó ID "btnDescargarGrupos" y una alerta de
                        confirmación con SweetAlert antes de ejecutar la acción.
                        ======================================================
                    --}}
                    <button class="btn-descargar-lista" id="btnDescargarGrupos">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> 
                        {{ __('messages.btn_download_groups') }}
                    </button>
                </div>
            </div>
            
            {{-- Tabla de alumnos --}}
            <div class="tabla-container">
                <table class="tabla-alumnos">
                    <thead>
                        <tr>
                            <th>{{ __('messages.column_number') }}</th>
                            <th>{{ __('messages.column_id') }}</th>
                            <th>{{ __('messages.column_name') }}</th>
                            <th>{{ __('messages.column_lastname') }}</th>
                            <th>Grupo</th>
                            <th>{{ __('messages.column_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="alumnosBody">
                        @forelse($alumnos as $i => $alumno)
                            <tr>
                                <td class="col-numero">{{ $i+1 }}</td>
                                <td class="col-matricula">{{ $alumno->matricula }}</td>
                                <td class="col-nombre">{{ $alumno->user?->name }}</td>
                                <td class="col-nombre">{{ $alumno->user?->apellido }}</td>
                                <td class="col-grupo">{{ $alumno->grupos->first()?->nombre ?? '—' }}</td>
                                <td class="col-acciones">
                                    <a href="{{ route('admin.alumno.expediente', $alumno->id) }}" style="text-decoration: none;">
                                        <button class="btn-icono-tabla" title="Ver expediente">
                                            <img src="{{ asset('img/expediente.svg') }}" alt="Expediente">
                                        </button>
                                    </a>
                                    <button class="btn-icono-tabla btn-editar-alumno"
                                            data-id="{{ $alumno->id }}"
                                            data-nombre="{{ $alumno->user?->name }}"
                                            data-apellido="{{ $alumno->user?->apellido }}"
                                            data-email="{{ $alumno->user?->email }}"
                                            data-matricula="{{ $alumno->matricula }}"
                                            data-curp="{{ $alumno->curp }}"
                                            data-sexo="{{ $alumno->sexo }}"
                                            data-fecha="{{ $alumno->fecha_nacimiento }}"
                                            data-telefono="{{ $alumno->telefono }}"
                                            data-grupo="{{ $alumno->grupos->first()?->id }}"
                                            title="Editar alumno">
                                        <img src="{{ asset('img/editar.png') }}" alt="Editar">
                                    </button>
                                    <form action="{{ route('admin.carrera.deleteAlumno', [$carrera, $alumno->id]) }}" method="POST" class="form-eliminar-inline" data-nombre="{{ $alumno->user?->name }} {{ $alumno->user?->apellido }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icono-tabla btn-eliminar" title="Eliminar alumno">
                                            <img src="{{ asset('img/borrar.svg') }}" alt="Eliminar">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No hay Resultados para esta tabla...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ======================================================
             CONTENIDO - MAESTROS
             ====================================================== 
             Muestra:
             - Botones: Agregar maestro y Descargar lista
             - Tabla con los maestros que imparten la carrera
        --}}
        <div class="tab-content" id="tab-maestros">
            
            {{-- Acciones --}}
            <div class="filtro-grupo" style="justify-content: flex-end;">
                <div class="botones-accion">
                    <button class="btn-agregar" id="btnAgregarMaestro">
                        {{ __('messages.btn_add_teacher') }}
                    </button>
                    {{-- 
                        ======================================================
                        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE DESCARGA
                        ======================================================
                        Se agregó ID "btnDescargarMaestros" y una alerta de
                        confirmación con SweetAlert antes de ejecutar la acción.
                        ======================================================
                    --}}
                    <button class="btn-descargar-lista" id="btnDescargarMaestros">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-descarga"> 
                        {{ __('messages.btn_download_teachers') }}
                    </button>
                </div>
            </div>
            
            {{-- Tabla de maestros --}}
            <div class="tabla-container">
                <table class="tabla-maestros">
                    <thead>
                        <tr>
                            <th>{{ __('messages.column_number') }}</th>
                            <th>{{ __('messages.column_name') }}</th>
                            <th>{{ __('messages.column_lastname') }}</th>
                            <th>{{ __('messages.column_email') }}</th>
                            <th>{{ __('messages.column_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="maestrosBody">
                        {{-- Solo mostrar maestros que están asignados a esta carrera --}}
                        @forelse($maestros as $i => $maestro)
                            <tr>
                                <td class="col-numero">{{ $i+1 }}</td>
                                <td class="col-nombre">{{ $maestro->user?->name }}</td>
                                <td class="col-nombre">{{ $maestro->user?->apellido }}</td>
                                <td class="col-correo">{{ $maestro->user?->email }}</td>
                                <td class="col-acciones">
                                    <a href="{{ route('admin.maestro.perfil', $maestro->id) }}" style="text-decoration: none;">
                                        <button class="btn-icono-tabla" title="Ver perfil">
                                            <img src="{{ asset('img/expediente.svg') }}" alt="Perfil">
                                        </button>
                                    </a>
                                    <button class="btn-icono-tabla btn-editar-maestro"
                                            data-id="{{ $maestro->id }}"
                                            data-nombre="{{ $maestro->user?->name }}"
                                            data-apellido="{{ $maestro->user?->apellido }}"
                                            data-email="{{ $maestro->user?->email }}"
                                            data-num-empleado="{{ $maestro->num_empleado }}"
                                            data-rfc="{{ $maestro->rfc }}"
                                            data-sexo="{{ $maestro->sexo }}"
                                            data-fecha="{{ $maestro->fecha_nacimiento }}"
                                            data-telefono="{{ $maestro->telefono }}"
                                            title="Editar maestro">
                                        <img src="{{ asset('img/editar.png') }}" alt="Editar">
                                    </button>
                                    <form action="{{ route('admin.carrera.deleteMaestro', [$carrera, $maestro->id]) }}" method="POST" class="form-eliminar-inline" data-nombre="{{ $maestro->user?->name }} {{ $maestro->user?->apellido }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icono-tabla btn-eliminar" title="Eliminar maestro">
                                            <img src="{{ asset('img/borrar.svg') }}" alt="Eliminar">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td>No hay Resultados para esta tabla...</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR ALUMNO
        ======================================================
        Se agregó ID "formAgregarAlumno" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        También se agregó validación de campos obligatorios.
        ======================================================
    --}}
    <div id="modalAgregarAlumno" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_student') }}</h3>
                <span class="modal-close" id="closeModalAlumno">&times;</span>
            </div>
            <form action="{{ route('admin.carrera.storeAlumno', $carrera) }}" method="POST" id="formAgregarAlumno">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input name="name" type="text" id="nombreAlumno" placeholder="Ej: Juan" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input name="apellido" type="text" id="apellidosAlumno" placeholder="Ej: Pérez García" required>
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupo" id="grupoAlumno" required>
                            <option value="">{{ __('messages.select_group') }}</option>
                            @foreach ($grupos as $grup)
                                <option value="{{ $grup->id }}">{{ $grup->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Matrícula</label>
                        <input name="matricula" type="text" id="matriculaAlumno" placeholder="Ej: UTN-2024-001" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input name="email" type="email" id="correoAlumno" placeholder="ejemplo@utnay.edu.mx" required>
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input name="curp" type="input" id="curpAlumno" placeholder="48932HJFIE" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="fechaNacAlumno" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input id="sexoAlumnoMas" name="sexo" type="radio" value="M" required></td>
                                <td><input id="sexoAlumnoFem" name="sexo" type="radio" value="F" required></td>
                                <td><input id="sexoAlumnoOt" name="sexo" type="radio" value="Otro" required></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Telefono</label>
                        <input name="telefono" type="input" id="telefonoAlumno" placeholder="3110006785">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="guardarAlumno">Guardar alumno</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR MAESTRO
        ======================================================
        Se agregó ID "formAgregarMaestro" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        El formulario no tiene action ni method porque la
        funcionalidad de backend se implementará posteriormente.
        ======================================================
    --}}
    <div id="modalAgregarMaestro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('messages.modal_add_teacher') }}</h3>
                <span class="modal-close" id="closeModalMaestro">&times;</span>
            </div>
            <form action="{{ route('admin.carrera.storeMaestro', $carrera) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.field_firstname') }}</label>
                        <input type="text" id="nombreMaestro" placeholder="{{  __('messages.placeholder_name')  }}" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_lastname') }}</label>
                        <input type="text" id="apellidosMaestro" placeholder="{{ __('messages.placeholder_lastname') }}" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_email') }}</label>
                        <input type="email" id="correoMaestro" placeholder="{{ __('messages.placeholder_email') }}" name="email" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_rfc') }}</label>
                        <input type="text" id="numEmpleado" placeholder="{{ __('messages.placeholder_rfc') }}" name="rfc" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_employee_num') }}</label>
                        <input type="text" id="numEmpleado" placeholder="{{ __('messages.placeholder_id_teacher') }}" name="num_empleado" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="fechaNacAlumno" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input id="sexoAlumnoMas" name="sexo" type="radio" value="M" required></td>
                                <td><input id="sexoAlumnoFem" name="sexo" type="radio" value="F" required></td>
                                <td><input id="sexoAlumnoOt" name="sexo" type="radio" value="Otro" required></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_phone') }}</label>
                        <input type="text" name="telefono" id="telefonoMaestro" placeholder="{{ __('messages.placeholder_phone') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">{{ __('messages.btn_save') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 
        ======================================================
        NOTA: CAMBIO REALIZADO - CONFIRMACIÓN DE GUARDAR CARRERA
        ======================================================
        Se agregó ID "formEditarCarrera" al formulario y una
        alerta de confirmación con SweetAlert antes de ejecutar
        la acción de guardado.
        
        También se agregó validación de campos obligatorios.
        ======================================================
    --}}
    <form action="{{ route('admin.update', $carrera) }}" method="POST" enctype="multipart/form-data" id="formEditarCarrera">
        @csrf
        @method('PATCH')
        <div id="modalCarrera" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>{{ __('messages.modal_edit_career') }}</h3>
                    <span class="modal-close" id="closeModalCarrera">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('messages.field_career_name') }}</label>
                        <input name="inNombre" type="text" id="nombreCarrera" value="{{ $carrera->nombre }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_career_key') }}</label>
                        <input name="inClave" type="text" id="claveCarrera" value="{{ $carrera->clave }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('messages.field_career_logo') }}</label>
                        <input name="inLogo" type="file" id="logoCarrera" accept="image/*">
                        <small class="form-text">{{ __('messages.helper_logo') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar" id="guardarCarrera">{{ __('messages.btn_save_changes') }}</button>
                </div>
            </div>
        </div>
    </form>

    {{-- MODAL AGREGAR GRUPO --}}
    <div id="modalAgregarGrupo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Agregar Grupo</h3>
                <span class="modal-close" id="closeModalGrupo">&times;</span>
            </div>
            <form action="{{ route('admin.carrera.storeGrupo', $carrera) }}" method="POST" id="formAgregarGrupo">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre del grupo</label>
                        <input name="nombre" type="text" id="nombreGrupo" placeholder="Ej: A" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label>Grado</label>
                        <select name="grado" id="gradoGrupo" required>
                            <option value="">Selecciona un grado</option>
                            @for($g = 1; $g <= 11; $g++)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">Guardar grupo</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR ALUMNO --}}
    <div id="modalEditarAlumno" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Alumno</h3>
                <span class="modal-close" id="closeModalEditarAlumno">&times;</span>
            </div>
            <form method="POST" id="formEditarAlumno">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input name="name" type="text" id="editAlumnoNombre" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input name="apellido" type="text" id="editAlumnoApellido" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input name="email" type="email" id="editAlumnoEmail" required>
                    </div>
                    <div class="form-group">
                        <label>Matrícula</label>
                        <input name="matricula" type="text" id="editAlumnoMatricula" required>
                    </div>
                    <div class="form-group">
                        <label>CURP</label>
                        <input name="curp" type="text" id="editAlumnoCurp" required>
                    </div>
                    <div class="form-group">
                        <label>Grupo</label>
                        <select name="grupo" id="editAlumnoGrupo" required>
                            <option value="">Selecciona un Grupo</option>
                            @foreach ($grupos as $grup)
                                <option value="{{ $grup->id }}">{{ $grup->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="editAlumnoFecha" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input name="sexo" type="radio" value="M" id="editAlumnoSexoM" required></td>
                                <td><input name="sexo" type="radio" value="F" id="editAlumnoSexoF"></td>
                                <td><input name="sexo" type="radio" value="Otro" id="editAlumnoSexoO"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input name="telefono" type="text" id="editAlumnoTelefono">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR MAESTRO --}}
    <div id="modalEditarMaestro" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Maestro</h3>
                <span class="modal-close" id="closeModalEditarMaestro">&times;</span>
            </div>
            <form method="POST" id="formEditarMaestro">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre(s)</label>
                        <input name="name" type="text" id="editMaestroNombre" required>
                    </div>
                    <div class="form-group">
                        <label>Apellidos</label>
                        <input name="apellido" type="text" id="editMaestroApellido" required>
                    </div>
                    <div class="form-group">
                        <label>Correo electrónico</label>
                        <input name="email" type="email" id="editMaestroEmail" required>
                    </div>
                    <div class="form-group">
                        <label>No. Empleado</label>
                        <input name="num_empleado" type="text" id="editMaestroNumEmpleado" required>
                    </div>
                    <div class="form-group">
                        <label>RFC</label>
                        <input name="rfc" type="text" id="editMaestroRfc" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha Nacimiento</label>
                        <input name="fecha_nacimiento" type="date" id="editMaestroFecha" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <table>
                            <tr style="text-align: center">
                                <td><label>Masculino</label></td>
                                <td><label>Femenino</label></td>
                                <td><label>Otro</label></td>
                            </tr>
                            <tr>
                                <td><input name="sexo" type="radio" value="M" id="editMaestroSexoM" required></td>
                                <td><input name="sexo" type="radio" value="F" id="editMaestroSexoF"></td>
                                <td><input name="sexo" type="radio" value="Otro" id="editMaestroSexoO"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input name="telefono" type="text" id="editMaestroTelefono">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDITAR GRUPO --}}
    <div id="modalEditarGrupo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Editar Grupo</h3>
                <span class="modal-close" id="closeModalEditarGrupo">&times;</span>
            </div>
            <form method="POST" id="formEditarGrupo">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Selecciona el grupo a editar</label>
                        <select id="selectGrupoEditar" class="grupo-select" required>
                            <option value="">Selecciona un grupo</option>
                            @foreach ($grupos as $grup)
                                <option value="{{ $grup->id }}"
                                    data-nombre="{{ $grup->nombre }}"
                                    data-grado="{{ $grup->grado }}"
                                    data-maestro="{{ $grup->maestro_id ?? '' }}">
                                    {{ $grup->nombre }} - Grado {{ $grup->grado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nombre del grupo</label>
                        <input name="nombre" type="text" id="editGrupoNombre" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label>Grado</label>
                        <select name="grado" id="editGrupoGrado" required>
                            <option value="">Selecciona un grado</option>
                            @for($g = 1; $g <= 11; $g++)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tutor / Maestro asignado</label>
                        <select name="maestro_id" id="editGrupoMaestro">
                            <option value="">Sin tutor asignado</option>
                            @foreach ($maestros as $m)
                                <option value="{{ $m->id }}">{{ $m->user?->name }} {{ $m->user?->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-guardar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL ELIMINAR GRUPO --}}
    <div id="modalEliminarGrupo" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Eliminar Grupo</h3>
                <span class="modal-close" id="closeModalEliminarGrupo">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Selecciona el grupo a eliminar</label>
                    <select id="selectGrupoEliminar" class="grupo-select" required>
                        <option value="">Selecciona un grupo</option>
                        @foreach ($grupos as $grup)
                            <option value="{{ $grup->id }}" data-nombre="{{ $grup->nombre }}">
                                {{ $grup->nombre }} - Grado {{ $grup->grado }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-guardar" id="btnConfirmarEliminarGrupo" style="background: #ef4444;">Eliminar</button>
            </div>
        </div>
    </div>
@endsection

{{-- 
    ======================================================
    SCRIPTS ADICIONALES
    ======================================================
    NOTA: FUNCIONALIDADES AGREGADAS
    ======================================================
    1. Confirmación antes de eliminar carrera.
    2. Confirmación antes de descargar lista de grupos.
    3. Confirmación antes de descargar lista de maestros.
    4. Confirmación antes de eliminar alumno.
    5. Confirmación antes de eliminar maestro.
    6. Confirmación antes de guardar alumno + validación de campos.
    7. Confirmación antes de guardar maestro + validación de campos.
    8. Confirmación antes de guardar carrera + validación de campos.
    
    La eliminación y guardado real permanecen a cargo del backend.
    ======================================================
--}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==============================================
        // 1. CAMBIO DE PESTAÑAS
        // ==============================================
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(`tab-${tabId}`).classList.add('active');
            });
        });

        // ==============================================
        // 2. MODAL AGREGAR ALUMNO
        // ==============================================
        const modalAlumno = document.getElementById('modalAgregarAlumno');
        const btnAgregarAlumno = document.getElementById('btnAgregarAlumno');

        if (btnAgregarAlumno) {
            btnAgregarAlumno.onclick = function() { modalAlumno.style.display = 'flex'; };
        }

        function cerrarModalAlumno() {
            modalAlumno.style.display = 'none';
            document.getElementById('formAgregarAlumno').reset();
        }

        document.getElementById('closeModalAlumno').onclick = cerrarModalAlumno;

        // ==============================================
        // 3. MODAL AGREGAR MAESTRO
        // ==============================================
        const modalMaestro = document.getElementById('modalAgregarMaestro');
        const btnAgregarMaestro = document.getElementById('btnAgregarMaestro');

        if (btnAgregarMaestro) {
            btnAgregarMaestro.onclick = function() { modalMaestro.style.display = 'flex'; };
        }

        function cerrarModalMaestro() {
            modalMaestro.style.display = 'none';
            document.getElementById('formAgregarMaestro').reset();
        }

        document.getElementById('closeModalMaestro').onclick = cerrarModalMaestro;

        // ==============================================
        // 4. MODAL EDITAR CARRERA
        // ==============================================
        const modalCarrera = document.getElementById('modalCarrera');
        const btnEditarCarrera = document.getElementById('btnEditarCarrera');

        if (btnEditarCarrera) {
            btnEditarCarrera.onclick = function() { modalCarrera.style.display = 'flex'; };
        }

        function cerrarModalCarrera() { modalCarrera.style.display = 'none'; }

        document.getElementById('closeModalCarrera').onclick = cerrarModalCarrera;

        // ==============================================
        // 5. MODAL AGREGAR GRUPO
        // ==============================================
        const modalGrupo = document.getElementById('modalAgregarGrupo');
        const btnAgregarGrupo = document.getElementById('btnAgregarGrupo');

        if (btnAgregarGrupo) {
            btnAgregarGrupo.onclick = function() { modalGrupo.style.display = 'flex'; };
        }

        function cerrarModalGrupo() {
            modalGrupo.style.display = 'none';
            document.getElementById('formAgregarGrupo').reset();
        }

        document.getElementById('closeModalGrupo').onclick = cerrarModalGrupo;

        // ==============================================
        // 6. MODAL EDITAR ALUMNO
        // ==============================================
        const modalEditarAlumno = document.getElementById('modalEditarAlumno');
        const closeModalEditarAlumno = document.getElementById('closeModalEditarAlumno');

        document.querySelectorAll('.btn-editar-alumno').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('formEditarAlumno').action = '{{ route("admin.carrera.updateAlumno", [$carrera, "__ID__"]) }}'.replace('__ID__', id);
                document.getElementById('editAlumnoNombre').value = this.dataset.nombre || '';
                document.getElementById('editAlumnoApellido').value = this.dataset.apellido || '';
                document.getElementById('editAlumnoEmail').value = this.getAttribute('data-email') || '';
                document.getElementById('editAlumnoMatricula').value = this.dataset.matricula || '';
                document.getElementById('editAlumnoCurp').value = this.dataset.curp || '';
                document.getElementById('editAlumnoFecha').value = this.dataset.fecha || '';
                document.getElementById('editAlumnoTelefono').value = this.dataset.telefono || '';
                document.getElementById('editAlumnoGrupo').value = this.dataset.grupo || '';

                const sexo = this.dataset.sexo;
                document.getElementById('editAlumnoSexoM').checked = (sexo === 'M');
                document.getElementById('editAlumnoSexoF').checked = (sexo === 'F');
                document.getElementById('editAlumnoSexoO').checked = (sexo === 'Otro');

                modalEditarAlumno.style.display = 'flex';
            });
        });

        function cerrarModalEditarAlumno() {
            modalEditarAlumno.style.display = 'none';
        }

        if (closeModalEditarAlumno) closeModalEditarAlumno.onclick = cerrarModalEditarAlumno;

        // ==============================================
        // 7. MODAL EDITAR MAESTRO
        // ==============================================
        const modalEditarMaestro = document.getElementById('modalEditarMaestro');
        const closeModalEditarMaestro = document.getElementById('closeModalEditarMaestro');

        document.querySelectorAll('.btn-editar-maestro').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('formEditarMaestro').action = '{{ route("admin.carrera.updateMaestro", [$carrera, "__ID__"]) }}'.replace('__ID__', id);
                document.getElementById('editMaestroNombre').value = this.dataset.nombre || '';
                document.getElementById('editMaestroApellido').value = this.dataset.apellido || '';
                document.getElementById('editMaestroEmail').value = this.dataset.email || '';
                document.getElementById('editMaestroNumEmpleado').value = this.dataset.numEmpleado || '';
                document.getElementById('editMaestroRfc').value = this.dataset.rfc || '';
                document.getElementById('editMaestroFecha').value = this.dataset.fecha || '';
                document.getElementById('editMaestroTelefono').value = this.dataset.telefono || '';

                const sexo = this.dataset.sexo;
                document.getElementById('editMaestroSexoM').checked = (sexo === 'M');
                document.getElementById('editMaestroSexoF').checked = (sexo === 'F');
                document.getElementById('editMaestroSexoO').checked = (sexo === 'Otro');

                modalEditarMaestro.style.display = 'flex';
            });
        });

        function cerrarModalEditarMaestro() {
            modalEditarMaestro.style.display = 'none';
        }

        if (closeModalEditarMaestro) closeModalEditarMaestro.onclick = cerrarModalEditarMaestro;

        // ==============================================
        // 8. MODAL EDITAR GRUPO
        // ==============================================
        const modalEditarGrupo = document.getElementById('modalEditarGrupo');
        const closeModalEditarGrupo = document.getElementById('closeModalEditarGrupo');
        const selectGrupoEditar = document.getElementById('selectGrupoEditar');
        const btnEditarGrupo = document.getElementById('btnEditarGrupo');

        if (btnEditarGrupo) {
            btnEditarGrupo.onclick = function() { modalEditarGrupo.style.display = 'flex'; };
        }

        selectGrupoEditar.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
                document.getElementById('editGrupoNombre').value = selected.dataset.nombre || '';
                document.getElementById('editGrupoGrado').value = selected.dataset.grado || '';
                document.getElementById('editGrupoMaestro').value = selected.dataset.maestro || '';
                document.getElementById('formEditarGrupo').action = '{{ route("admin.carrera.updateGrupo", [$carrera, "__ID__"]) }}'.replace('__ID__', selected.value);
            }
        });

        function cerrarModalEditarGrupo() {
            modalEditarGrupo.style.display = 'none';
            selectGrupoEditar.value = '';
            document.getElementById('formEditarGrupo').reset();
        }

        if (closeModalEditarGrupo) closeModalEditarGrupo.onclick = cerrarModalEditarGrupo;

        // ==============================================
        // 9. CERRAR MODALES AL HACER CLIC FUERA
        // ==============================================
        window.onclick = function(e) {
            if (e.target === modalAlumno) cerrarModalAlumno();
            if (e.target === modalMaestro) cerrarModalMaestro();
            if (e.target === modalCarrera) cerrarModalCarrera();
            if (e.target === modalGrupo) cerrarModalGrupo();
            if (e.target === modalEditarAlumno) cerrarModalEditarAlumno();
            if (e.target === modalEditarMaestro) cerrarModalEditarMaestro();
            if (e.target === modalEditarGrupo) cerrarModalEditarGrupo();
        };

        // ==============================================
        // 10. CONFIRMACIONES DE FORMULARIOS
        // ==============================================

        // Eliminar carrera
        const formEliminarCarrera = document.getElementById('formEliminarCarrera');
        if (formEliminarCarrera) {
            formEliminarCarrera.addEventListener('submit', function(e) {
                e.preventDefault();
                confirmarAccion('Confirmar eliminación', '¿Deseas continuar con esta acción?').then(r => {
                    if (r.isConfirmed) formEliminarCarrera.submit();
                });
            });
        }

        // Eliminar alumno (formularios reales)
        document.querySelectorAll('.form-eliminar-inline').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const nombre = this.dataset.nombre || 'este registro';
                confirmarAccion('Confirmar eliminación', `¿Estás seguro de que deseas eliminar a "${nombre}"?`, 'Eliminar', 'Cancelar').then(r => {
                    if (r.isConfirmed) form.submit();
                });
            });
        });

        // Eliminar grupo (modal con select)
        const modalEliminarGrupo = document.getElementById('modalEliminarGrupo');
        const btnEliminarGrupo = document.getElementById('btnEliminarGrupo');
        const selectGrupoEliminar = document.getElementById('selectGrupoEliminar');
        const btnConfirmarEliminarGrupo = document.getElementById('btnConfirmarEliminarGrupo');

        if (btnEliminarGrupo) {
            btnEliminarGrupo.onclick = function() { modalEliminarGrupo.style.display = 'flex'; };
        }

        document.getElementById('closeModalEliminarGrupo').onclick = function() {
            modalEliminarGrupo.style.display = 'none';
            selectGrupoEliminar.value = '';
        };

        if (btnConfirmarEliminarGrupo) {
            btnConfirmarEliminarGrupo.addEventListener('click', function() {
                const grupoId = selectGrupoEliminar.value;
                if (!grupoId) {
                    alertaInfo('Sin selección', 'Selecciona un grupo para eliminar.');
                    return;
                }
                const nombre = selectGrupoEliminar.options[selectGrupoEliminar.selectedIndex].dataset.nombre || 'este grupo';
                confirmarAccion('Eliminar grupo', `¿Estás seguro de que deseas eliminar el grupo "${nombre}"? Los alumnos se desvincularán.`, 'Eliminar', 'Cancelar').then(r => {
                    if (r.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("admin.carrera.deleteGrupo", [$carrera, "__ID__"]) }}'.replace('__ID__', grupoId);
                        form.innerHTML = '@csrf <input type="hidden" name="_method" value="DELETE">';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        }

        // Guardar alumno (nuevo)
        const formAgregarAlumno = document.getElementById('formAgregarAlumno');
        if (formAgregarAlumno) {
            formAgregarAlumno.addEventListener('submit', function(e) {
                e.preventDefault();
                const f = this;
                const nombre = f.querySelector('[name="name"]').value.trim();
                const apellido = f.querySelector('[name="apellido"]').value.trim();
                if (!nombre || !apellido) {
                    alertaInfo('Campos incompletos', 'Debes completar todos los campos obligatorios.');
                    return;
                }
                confirmarAccion('Guardar alumno', '¿Estás seguro de que quieres guardar este alumno?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) f.submit();
                });
            });
        }

        // Editar alumno
        const formEditarAlumno = document.getElementById('formEditarAlumno');
        if (formEditarAlumno) {
            formEditarAlumno.addEventListener('submit', function(e) {
                e.preventDefault();
                confirmarAccion('Actualizar alumno', '¿Estás seguro de que quieres guardar los cambios?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) formEditarAlumno.submit();
                });
            });
        }

        // Guardar maestro (nuevo)
        const formAgregarMaestro = document.getElementById('formAgregarMaestro');
        if (formAgregarMaestro) {
            formAgregarMaestro.addEventListener('submit', function(e) {
                e.preventDefault();
                const f = this;
                const nombre = f.querySelector('[name="name"]').value.trim();
                const apellido = f.querySelector('[name="apellido"]').value.trim();
                if (!nombre || !apellido) {
                    alertaInfo('Campos incompletos', 'Debes completar todos los campos obligatorios.');
                    return;
                }
                confirmarAccion('Guardar maestro', '¿Estás seguro de que quieres guardar este maestro?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) f.submit();
                });
            });
        }

        // Editar maestro
        const formEditarMaestro = document.getElementById('formEditarMaestro');
        if (formEditarMaestro) {
            formEditarMaestro.addEventListener('submit', function(e) {
                e.preventDefault();
                confirmarAccion('Actualizar maestro', '¿Estás seguro de que quieres guardar los cambios?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) formEditarMaestro.submit();
                });
            });
        }

        // Editar grupo
        const formEditarGrupo = document.getElementById('formEditarGrupo');
        if (formEditarGrupo) {
            formEditarGrupo.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!selectGrupoEditar.value) {
                    alertaInfo('Sin selección', 'Selecciona un grupo para editar.');
                    return;
                }
                confirmarAccion('Actualizar grupo', '¿Estás seguro de que quieres guardar los cambios?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) formEditarGrupo.submit();
                });
            });
        }

        // Guardar grupo (nuevo)
        const formAgregarGrupo = document.getElementById('formAgregarGrupo');
        if (formAgregarGrupo) {
            formAgregarGrupo.addEventListener('submit', function(e) {
                e.preventDefault();
                const nombre = document.getElementById('nombreGrupo').value.trim();
                const grado = document.getElementById('gradoGrupo').value;
                if (!nombre || !grado) {
                    alertaInfo('Campos incompletos', 'Debes completar todos los campos obligatorios.');
                    return;
                }
                confirmarAccion('Guardar grupo', '¿Estás seguro de que quieres crear este grupo?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) formAgregarGrupo.submit();
                });
            });
        }

        // Editar carrera
        const formEditarCarrera = document.getElementById('formEditarCarrera');
        if (formEditarCarrera) {
            formEditarCarrera.addEventListener('submit', function(e) {
                e.preventDefault();
                const nombre = document.getElementById('nombreCarrera').value.trim();
                const clave = document.getElementById('claveCarrera').value.trim();
                if (!nombre || !clave) {
                    alertaInfo('Campos incompletos', 'Debes completar todos los campos obligatorios.');
                    return;
                }
                confirmarAccion('Guardar cambios', '¿Estás seguro de que quieres guardar los cambios?', 'Guardar', 'Cancelar').then(r => {
                    if (r.isConfirmed) formEditarCarrera.submit();
                });
            });
        }

        // Descargar lista de grupos
        const btnDescargarGrupos = document.getElementById('btnDescargarGrupos');
        if (btnDescargarGrupos) {
            btnDescargarGrupos.addEventListener('click', function(e) {
                e.preventDefault();
                confirmarAccion('Descargar lista de grupos', '¿Deseas continuar con esta acción?').then(r => {
                    if (r.isConfirmed) alertaInfo('Descarga de lista', 'La funcionalidad de descarga se integrará próximamente.');
                });
            });
        }

        // Descargar lista de maestros
        const btnDescargarMaestros = document.getElementById('btnDescargarMaestros');
        if (btnDescargarMaestros) {
            btnDescargarMaestros.addEventListener('click', function(e) {
                e.preventDefault();
                confirmarAccion('Descargar lista de maestros', '¿Deseas continuar con esta acción?').then(r => {
                    if (r.isConfirmed) alertaInfo('Descarga de lista', 'La funcionalidad de descarga se integrará próximamente.');
                });
            });
        }

    });
</script>
@endpush