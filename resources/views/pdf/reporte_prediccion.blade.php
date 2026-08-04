<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Predicción Académica</title>
    <style>
        body { font-family: sans-serif; }
        .container { padding: 20px; }
        h1 { color: #333; }
        .data-item { margin-bottom: 10px; }
        label { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reporte de Predicción Académica</h1>
        <p>Alumno: {{ $alumno->user->name }} {{ $alumno->user->apellido }}</p>
        <hr>
        <div class="data-item">
            <label>Predicción de nota:</label> {{ $data['prediccion_nota'] }}
        </div>
        <div class="data-item">
            <label>Estatus de riesgo:</label> {{ $data['estatus_riesgo'] }}
        </div>
        <div class="data-item">
            <label>Perfil (Cluster):</label> {{ $data['cluster_nombre'] }}
        </div>
        <div class="data-item">
            <label>Recomendación:</label> {{ $data['recomendacion'] }}
        </div>
    </div>
</body>
</html>
