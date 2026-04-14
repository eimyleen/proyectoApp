@extends('layouts.dashboard')

@section('title', 'Expediente del Alumno - Maestro')

@section('subtitle', 'Consulta la información académica del alumno')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/maestro/grupos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <div class="expediente-container">
        <!-- Botón generar PDF -->
        <div class="pdf-button-container">
            <button class="btn-generar-pdf">
                <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-pdf">
                Generar expediente PDF
            </button>
        </div>

        <!-- Foto de perfil -->
        <div class="perfil-section">
            <div class="avatar-grande">
                <span class="avatar-iniciales-grande"></span>
            </div>
        </div>

        <!-- Datos personales del alumno -->
        <h3 class="seccion-titulo">Datos personales</h3>
        <div class="datos-grid">
        <div class="dato-item">
            <label>Nombre</label>
            <span class="dato-valor">{{ $alumno->user->name }}</span> {{--  --}}
        </div>
        
        <div class="dato-item">
            <label>Apellidos</label>
            <span class="dato-valor">{{ $alumno->user->apellido }}</span> {{-- [cite: 4] --}}
        </div>

        <div class="dato-item">
            <label>Matrícula</label>
            <span class="dato-valor">{{ $alumno->matricula }}</span> {{-- [cite: 4, 5] --}}
        </div>

        <div class="dato-item">
            <label>Carrera</label>
            <span class="dato-valor">{{ $alumno->carrera->nombre ?? 'N/A' }}</span> {{-- [cite: 5] --}}
        </div>

        <div class="dato-item">
            <label>Grupo</label>
            <span class="dato-valor">{{ $alumno->grupo }}</span> {{-- [cite: 5, 6] --}}
        </div>

        <div class="dato-item">
            <label>CURP</label>
            <span class="dato-valor">{{ $alumno->curp }}</span> {{-- [cite: 6] --}}
        </div>

        <div class="dato-item">
            <label>Edad</label>
            <span class="dato-valor">{{ $alumno->edad }} años</span> {{-- [cite: 7] --}}
        </div>

        <div class="dato-item">
            <label>Sexo</label>
            <span class="dato-valor">{{ $alumno->sexo }}</span> {{-- [cite: 7] --}}
        </div>

        <div class="dato-item">
            <label>Fecha de nacimiento</label>
            <span class="dato-valor">{{ $alumno->fecha_nacimiento }}</span> {{-- [cite: 8] --}}
        </div>

        <div class="dato-item">
            <label>Correo electrónico</label>
            <span class="dato-valor">{{ $alumno->user->email }}</span> {{-- [cite: 8, 9] --}}
        </div>
    </div>

        <!-- Sección de calificaciones con filtro de período -->
        <h3 class="seccion-titulo">Calificaciones</h3>
        
        <!-- Filtro de período -->
        <div class="filtro-periodo-expediente">
            <div class="periodo-select-expediente">
                <label>Período:</label>
                <select id="periodoSelect">
                    <option value="">Seleccionar período</option>
                    <!-- Los períodos se cargarán dinámicamente desde el backend -->
                </select>
            </div>
        </div>

        <!-- Tabla de calificaciones -->
        <div class="tabla-container">
            <table class="tabla-calificaciones" id="tablaCalificaciones">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody id="calificacionesBody">
                    @for($i = 0; $i < 5; $i++)
                        <tr>
                            <td></td>
                            <td class="calificacion"></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Separador visual entre secciones -->
        <div class="separador-secciones"></div>

        <!-- SECCIÓN DE TUTORÍAS - Solo visible para maestros que son TUTOR -->
        <!-- El backend debe mostrar esta sección solo si $esTutor = true -->
        <div class="tutorias-header">
            <h3 class="seccion-titulo tutorias-titulo">Tutorías</h3>
            <button class="btn-agregar-tutoria" id="btnAgregarTutoria">+ Agregar tutoría</button>
        </div>
        <div class="tabla-container">
            <table class="tabla-tutorias">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tema</th>
                        <th>Notas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tutoriasBody">
                    @for($i = 0; $i < 2; $i++)
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button class="btn-editar-tutoria">Editar</button></td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <!-- FIN SECCIÓN TUTORÍAS -->

    </div>

    <!-- Modal para agregar/editar tutoría -->
    <div id="modalTutoria" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Agregar tutoría</h3>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Fecha</label>
                    <input type="date" id="fechaTutoria">
                </div>
                <div class="form-group">
                    <label>Tema</label>
                    <input type="text" id="temaTutoria" placeholder="Ej: Revisión de calificaciones">
                </div>
                <div class="form-group">
                    <label>Notas</label>
                    <textarea id="notasTutoria" rows="3" placeholder="Observaciones adicionales..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" id="cancelarModal">Cancelar</button>
                <button class="btn-guardar" id="guardarTutoria">Guardar tutoría</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filtro de período para calificaciones
        const periodoSelect = document.getElementById('periodoSelect');
        const calificacionesBody = document.getElementById('calificacionesBody');
        
        if (periodoSelect) {
            periodoSelect.addEventListener('change', function() {
                const periodo = this.value;
                if (periodo) {
                    // Aquí el backend cargará las calificaciones del período seleccionado
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                } else {
                    // Mostrar 5 filas vacías
                    calificacionesBody.innerHTML = '';
                    for (let i = 0; i < 5; i++) {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td></td><td class="calificacion"></td>`;
                        calificacionesBody.appendChild(row);
                    }
                }
            });
        }

        // Modal de tutorías
        const modal = document.getElementById('modalTutoria');
        const btnAgregar = document.getElementById('btnAgregarTutoria');
        const closeModal = document.getElementById('closeModal');
        const cancelarModal = document.getElementById('cancelarModal');
        const modalTitulo = document.getElementById('modalTitulo');

        if (btnAgregar) {
            btnAgregar.addEventListener('click', function() {
                modalTitulo.textContent = 'Agregar tutoría';
                document.getElementById('fechaTutoria').value = '';
                document.getElementById('temaTutoria').value = '';
                document.getElementById('notasTutoria').value = '';
                modal.style.display = 'flex';
            });
        }

        function cerrarModal() {
            modal.style.display = 'none';
        }

        if (closeModal) closeModal.addEventListener('click', cerrarModal);
        if (cancelarModal) cancelarModal.addEventListener('click', cerrarModal);

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                cerrarModal();
            }
        });

        const guardarBtn = document.getElementById('guardarTutoria');
        if (guardarBtn) {
            guardarBtn.addEventListener('click', function() {
                const fecha = document.getElementById('fechaTutoria').value;
                const tema = document.getElementById('temaTutoria').value;
                
                if (fecha && tema) {
                    alert('Tutoría guardada correctamente');
                    cerrarModal();
                } else {
                    alert('Por favor completa la fecha y el tema');
                }
            });
        }

        document.querySelectorAll('.btn-editar-tutoria').forEach(btn => {
            btn.addEventListener('click', function() {
                modalTitulo.textContent = 'Editar tutoría';
                const row = this.closest('tr');
                const fecha = row.cells[0].textContent;
                const tema = row.cells[1].textContent;
                const notas = row.cells[2].textContent;
                
                if (fecha && fecha.includes('/')) {
                    const partes = fecha.split('/');
                    document.getElementById('fechaTutoria').value = `${partes[2]}-${partes[1]}-${partes[0]}`;
                } else {
                    document.getElementById('fechaTutoria').value = '';
                }
                document.getElementById('temaTutoria').value = tema;
                document.getElementById('notasTutoria').value = notas;
                modal.style.display = 'flex';
            });
        });
    });
</script>
@endpush