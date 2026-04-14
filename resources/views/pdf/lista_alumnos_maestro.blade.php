<!DOCTYPE html>
<html>
<head>
    <title>Lista Global de Alumnos</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>
    <h2>{{ __('messages.modal_global_title') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.label_id_number') }}</th>
                <th>{{ __('messages.th_nombre') }}</th>
                <th>{{ __('messages.label_major') }}</th>
                <th>{{ __('messages.label_group') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($alumnos as $alumno)
                <tr>
                    <td>{{ $alumno->matricula }}</td>
                    <td>{{ $alumno->user?->name }} {{ $alumno->user?->apellido }}</td>
                    <td>{{ $alumno->carrera?->nombre }}</td>
                    <td>{{ $alumno->grupo }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>