<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Análisis de Grupo</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .header { text-align: center; margin-bottom: 20px; }
        .summary { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Análisis Académico</h1>
        <h2>Grupo: {{ $grupo->nombre }}</h2>
    </div>
    
    <div class="summary">
        <p><strong>Promedio General Proyectado:</strong> {{ $analisis['promedio_grupo_proyectado'] }}</p>
        <p><strong>Distribución de Riesgo:</strong> 
            Alto: {{ $analisis['distribucion_riesgo']['Alto'] }}, 
            Medio: {{ $analisis['distribucion_riesgo']['Medio'] }}, 
            Bajo: {{ $analisis['distribucion_riesgo']['Bajo'] }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Riesgo Académico</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $alumno)
            @php
                $riesgo = 'Bajo';
                foreach($analisis['alumnos_riesgo'] as $a) {
                    if($a['alumno_id'] == $alumno->id) {
                        $riesgo = $a['riesgo'];
                        break;
                    }
                }
            @endphp
            <tr>
                <td>{{ $alumno->user->name }}</td>
                <td>{{ $alumno->user->apellido }}</td>
                <td>{{ $riesgo }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
