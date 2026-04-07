<!-- Dashboard Maestro - Módulo de carreras que imparte -->
@extends('layouts.dashboard')

@section('title', 'Panel Maestro')
@section('user-role', 'Maestro')
@section('avatar-iniciales', 'CS')
@section('nombre-completo', 'Carlos Sánchez')
@section('welcome-message', '¡Bienvenido, Carlos!')
@section('subtitle', 'Selecciona una carrera para gestionar sus grupos')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_maestro.css') }}">
@endpush

@section('content')
    <!-- Botones superiores -->
    <div class="maestro-buttons">
        <button class="btn-lista-global" id="btnListaGlobal">Ver lista de alumnos global</button>
    </div>

    <!-- Carreras -->
    <div class="carreras-container">
        <div class="carreras-grid">
            @php
                $carreras = [
                    ['img' => 'ing_alimentos.png', 'alt' => 'Ingeniería en Alimentos'],
                    ['img' => 'ing_civil.png', 'alt' => 'Ingeniería Civil'],
                    ['img' => 'ing_inte_artificial.png', 'alt' => 'Ingeniería Artificial'],
                    ['img' => 'ing_logistica.png', 'alt' => 'Ingeniería Logística'],
                    ['img' => 'ing_mant_industrial.png', 'alt' => 'Ingeniería Mantenimiento Industrial'],
                    ['img' => 'ing_mecatronica.png', 'alt' => 'Ingeniería Mecatrónica'],
                    ['img' => 'ing_micro_semic.png', 'alt' => 'Ingeniería Micro Semiconductores'],
                    ['img' => 'ing_tec_info.png', 'alt' => 'Ingeniería Tecnologías Información'],
                    ['img' => 'lic_admin.png', 'alt' => 'Licenciatura Administración'],
                    ['img' => 'lic_gastro.png', 'alt' => 'Gastronomía'],
                    ['img' => 'lic_merca.png', 'alt' => 'Licenciatura Mercadotecnia'],
                    ['img' => 'lic_psicologia.png', 'alt' => 'Psicología'],
                    ['img' => 'lic_seg_publ.png', 'alt' => 'Seguridad Pública'],
                    ['img' => 'lic_turismo.png', 'alt' => 'Licenciatura Turismo'],
                ];
            @endphp

            @foreach($carreras as $carrera)
                <div class="carrera-card" data-carrera="{{ $carrera['alt'] }}">
                    <div class="carrera-img">
                        <img src="{{ asset('img/carreras/' . $carrera['img']) }}" alt="{{ $carrera['alt'] }}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para lista global de alumnos -->
    <div id="modalListaGlobal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Lista de alumnos global</h3>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <div class="modal-filtro">
                        <img src="{{ asset('img/lupa.png') }}" alt="Buscar" class="lupa-icon-modal">
                        <input type="text" id="busquedaModal" placeholder="Buscar por nombre o matrícula..." class="input-busqueda-modal">
                    </div>
                    <button class="btn-descargar-modal">
                        <img src="{{ asset('img/descargas.png') }}" alt="Descargar" class="btn-icon-modal">
                        Descargar lista
                    </button>
                </div>
                <div class="tabla-container">
                    <table class="tabla-alumnos-global" id="tablaAlumnosModal">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Carrera</th>
                                <th>Grupo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < 5; $i++)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal lista global
        const modal = document.getElementById('modalListaGlobal');
        const btn = document.getElementById('btnListaGlobal');
        const closeBtn = document.getElementById('closeModal');

        if (btn && modal && closeBtn) {
            btn.addEventListener('click', function() {
                modal.style.display = 'flex';
            });

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Filtro dentro del modal
        const inputBusquedaModal = document.getElementById('busquedaModal');
        if (inputBusquedaModal) {
            inputBusquedaModal.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                const filas = document.querySelectorAll('#tablaAlumnosModal tbody tr');
                
                filas.forEach(fila => {
                    const texto = fila.innerText.toLowerCase();
                    if (texto.includes(busqueda) || busqueda === '') {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                });
            });
        }

        // Click en carrera
        document.querySelectorAll('.carrera-card').forEach(card => {
            card.addEventListener('click', function() {
                const carrera = this.getAttribute('data-carrera');
                alert('Navegar a grupos de: ' + carrera);
                // window.location.href = '/dashboard/maestro/grupos';
            });
        });
    });
</script>
@endpush