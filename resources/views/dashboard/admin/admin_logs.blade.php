@extends('layouts.dashboard')

@section('title', __('messages.logs_title'))

@section('subtitle', __('messages.logs_subtitle'))

@section('title', 'Logs del Sistema')

@section('subtitle', 'Consulta el historial de actividades')

@section('back-button')
    <!-- Activa el botón de regreso -->
@endsection

@section('back-url', '/dashboard/admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_admin.css') }}">
@endpush

@section('content')
    <div class="logs-container">
        <div class="page-header">
            <h2>{{ __('messages.logs_header') }}</h2>
        </div>

        <div class="tabla-logs">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('messages.column_id_log') }}</th>
                        <th>{{ __('messages.column_datetime') }}</th>
                        <th>{{ __('messages.column_user') }}</th>
                        <th>{{ __('messages.column_role') }}</th>
                        <th>{{ __('messages.column_action') }}</th>
                        <th>{{ __('messages.column_description') }}</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody">
                    @foreach($logs as $log)
                        <tr>
                            <td class="id">{{ $log->id }}</td>
                            <td class="fecha">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="usuario">{{ $log->user->name }} {{ $log->user->apellido }}</td>
                            <td class="rol">
                                <span class="badge-rol {{ $log->user->role }}">
                                    {{ ucfirst($log->user->role) }}
                                </span>
                            </td>
                            <td class="accion">{{ $log->accion }}</td>
                            <td class="descripcion">{{ $log->descripcion ?? __('messages.log_no_detail') }}</td> </tr>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // script 
        console.log('Página de logs cargada');
    });
</script>
@endpush