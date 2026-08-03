<!DOCTYPE html>
<html>
<head>
    <title>Expediente Personal</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 100%; margin-top: 20px; }
        h2 { text-align: center; color: #333; }
        .datos { margin-top: 20px; }
        .dato-item { margin-bottom: 10px; }
        .dato-item label { font-weight: bold; }
    </style>
</head>
<body>
    <h2>Expediente Personal del Alumno</h2>
    <div class="container">
        <div class="datos">
            <div class="dato-item"><label>Nombre:</label> {{ $alumno->user->name }} {{ $alumno->user->apellido }}</div>
            <div class="dato-item"><label>Matrícula:</label> {{ $alumno->matricula }}</div>
            <div class="dato-item"><label>Carrera:</label> {{ $carrera->nombre ?? 'N/A' }}</div>
            <div class="dato-item"><label>Grupo:</label> {{ $grupo->nombre ?? 'N/A' }}</div>
            <div class="dato-item"><label>CURP:</label> {{ $alumno->curp }}</div>
            <div class="dato-item"><label>Fecha de Nacimiento:</label> {{ $alumno->fecha_nacimiento }}</div>
            <div class="dato-item"><label>Correo electrónico:</label> {{ $alumno->user->email }}</div>
            <div class="dato-item"><label>Teléfono:</label> {{ $alumno->telefono ?? 'N/A' }}</div>
        </div>
    </div>
</body>
</html>