<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTNay - Sistema de Expedientes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f5f5f5;
            color: #1f2937;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 48px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #06b6d4;
            margin-bottom: 8px;
        }

        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }

        .rol-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-top: 4px solid;
        }

        .rol-card.alumno { border-top-color: #06b6d4; }
        .rol-card.maestro { border-top-color: #10b981; }
        .rol-card.admin { border-top-color: #8b5cf6; }

        .rol-card h2 {
            font-size: 1.5rem;
            margin-bottom: 16px;
        }

        .rol-card ul {
            list-style: none;
        }

        .rol-card li {
            margin-bottom: 12px;
            padding-left: 20px;
            position: relative;
        }

        .rol-card li::before {
            content: '→';
            position: absolute;
            left: 0;
            color: #06b6d4;
        }

        .rol-card a {
            text-decoration: none;
            color: #1f2937;
            display: block;
            font-size: 0.9rem;
        }

        .rol-card a:hover {
            color: #06b6d4;
        }

        .badge {
            display: inline-block;
            background: #e5e7eb;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
            margin-left: 8px;
            color: #6b7280;
        }

        .mapa-container {
            background: white;
            border-radius: 24px;
            padding: 32px;
            margin-bottom: 48px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            overflow-x: auto;
        }

        .mapa-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 24px;
        }

        .mapa-grid {
            display: flex;
            flex-direction: column;
            gap: 20px;
            min-width: 800px;
        }

        .mapa-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .mapa-node {
            background: #f9fafb;
            border-radius: 12px;
            padding: 12px 20px;
            text-align: center;
            min-width: 160px;
            border: 1px solid #e5e7eb;
        }

        .mapa-node.principal {
            background: linear-gradient(135deg, #06b6d4, #10b981);
            color: white;
            border: none;
        }

        .mapa-node a {
            text-decoration: none;
            color: #1f2937;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .mapa-arrow {
            font-size: 1.2rem;
            color: #9ca3af;
        }

        .tablas-container {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .tabla-bd {
            margin-bottom: 40px;
        }

        .tabla-bd h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 16px;
            background: #f9fafb;
            padding: 12px 16px;
            border-radius: 12px;
            border-left: 4px solid #06b6d4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            text-align: left;
            padding: 10px 12px;
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            color: #4b5563;
            font-size: 0.85rem;
        }

        .ejemplo {
            color: #06b6d4;
            font-family: monospace;
            font-size: 0.8rem;
        }

        footer {
            text-align: center;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 0.8rem;
        }

        @media (max-width: 900px) {
            .roles-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistema de Expedientes UTNay</h1>
            <p>Plataforma para consulta y gestion de expedientes academicos</p>
        </div>

        <!-- Tarjetas de navegación por rol -->
        <div class="roles-grid">
            <div class="rol-card alumno">
                <h2>Alumno</h2>
                <ul>
                    <li><a href="/dashboard/alumno">Dashboard principal</a><span class="badge">Materias y Horario</span></li>
                    <li><a href="/dashboard/alumno/expediente">Expediente personal</a><span class="badge">Datos + Foto carrera</span></li>
                    <li><a href="/dashboard/alumno/calificaciones">Calificaciones</a><span class="badge">Filtro por periodo</span></li>
                </ul>
            </div>
            <div class="rol-card maestro">
                <h2>Maestro</h2>
                <ul>
                    <li><a href="/dashboard/maestro">Dashboard principal</a><span class="badge">Carreras que imparte</span></li>
                    <li><a href="/dashboard/maestro/perfil">Mi Perfil</a><span class="badge">Datos + Carreras + Grupo tutorado</span></li>
                    <li><a href="/dashboard/maestro/grupos">Grupos de carrera</a><span class="badge">Lista de alumnos por grupo</span></li>
                    <li><a href="/dashboard/maestro/expediente/alumno">Expediente del alumno</a><span class="badge">Datos + Calificaciones + Tutorias</span></li>
                </ul>
            </div>
            <div class="rol-card admin">
                <h2>Administrador</h2>
                <ul>
                    <li><a href="/dashboard/admin">Dashboard principal</a><span class="badge">Todas las carreras</span></li>
                    <li><a href="/dashboard/admin/carrera">Detalle de carrera</a><span class="badge">Grupos + Maestros</span></li>
                    <li><a href="/dashboard/admin/perfil">Mi Perfil</a><span class="badge">Editar perfil + Cambiar contrasena</span></li>
                    <li><a href="/dashboard/admin/maestro/perfil">Perfil de maestro</a><span class="badge">Editar/Eliminar</span></li>
                    <li><a href="/dashboard/admin/alumno/perfil">Perfil de alumno</a><span class="badge">Editar/Eliminar + Tutorias</span></li>
                    <li><a href="/dashboard/admin/alumno/expediente">Expediente de alumno</a><span class="badge">Vista completa</span></li>
                </ul>
            </div>
        </div>

        <!-- Mapa de navegacion -->
        <div class="mapa-container">
            <div class="mapa-title">Mapa de navegacion</div>
            <div class="mapa-grid">
                <div class="mapa-row">
                    <div class="mapa-node principal">Login</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node principal">Dashboard</div>
                </div>
                <div class="mapa-row">
                    <div class="mapa-node">Alumno</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Materias y Horario</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Expediente</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Calificaciones</div>
                </div>
                <div class="mapa-row">
                    <div class="mapa-node">Maestro</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Carreras</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Grupos</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Lista de alumnos</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Expediente alumno</div>
                </div>
                <div class="mapa-row">
                    <div class="mapa-node">Admin</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Carreras</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Detalle carrera</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Grupos / Maestros</div>
                    <span class="mapa-arrow">→</span>
                    <div class="mapa-node">Perfiles (CRUD)</div>
                </div>
            </div>
        </div>

        <!-- Documentacion para backend -->
        <div class="tablas-container">
            <h2 style="margin-bottom: 24px;">Referencia para Backend</h2>
            <p style="margin-bottom: 24px; color: #6b7280;">Estructura basica de datos que el front end espera recibir.</p>

            <!-- Tabla: Usuarios -->
            <div class="tabla-bd">
                <h3>Usuarios</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>nombre</td><td>VARCHAR</td><td class="ejemplo">Carlos</td></tr>
                        <tr><td>apellido</td><td>VARCHAR</td><td class="ejemplo">Martinez Garcia</td></tr>
                        <tr><td>email</td><td>VARCHAR</td><td class="ejemplo">carlos@utnay.edu.mx</td></tr>
                        <tr><td>rol</td><td>ENUM</td><td class="ejemplo">alumno, maestro, admin</td></tr>
                        <tr><td>foto</td><td>VARCHAR</td><td class="ejemplo">/img/fotos/usuario.jpg</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Alumnos -->
            <div class="tabla-bd">
                <h3>Alumnos</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>usuario_id</td><td>INT</td><td class="ejemplo">1 (FK a usuarios)</td></tr>
                        <tr><td>matricula</td><td>VARCHAR</td><td class="ejemplo">UTN-2024-001</td></tr>
                        <tr><td>carrera_id</td><td>INT</td><td class="ejemplo">1 (FK a carreras)</td></tr>
                        <tr><td>grupo</td><td>VARCHAR</td><td class="ejemplo">8A</td></tr>
                        <tr><td>curp</td><td>VARCHAR</td><td class="ejemplo">MAGC040101HDFRRR09</td></tr>
                        <tr><td>edad</td><td>INT</td><td class="ejemplo">21</td></tr>
                        <tr><td>sexo</td><td>VARCHAR</td><td class="ejemplo">Masculino</td></tr>
                        <tr><td>fecha_nacimiento</td><td>DATE</td><td class="ejemplo">2004-01-01</td></tr>
                        <tr><td>telefono</td><td>VARCHAR</td><td class="ejemplo">311-123-4567</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Maestros -->
            <div class="tabla-bd">
                <h3>Maestros</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>usuario_id</td><td>INT</td><td class="ejemplo">2 (FK a usuarios)</td></tr>
                        <tr><td>num_empleado</td><td>VARCHAR</td><td class="ejemplo">EMP-2024-001</td></tr>
                        <tr><td>rfc</td><td>VARCHAR</td><td class="ejemplo">SAHC840101HDFRRR09</td></tr>
                        <tr><td>edad</td><td>INT</td><td class="ejemplo">42</td></tr>
                        <tr><td>sexo</td><td>VARCHAR</td><td class="ejemplo">Masculino</td></tr>
                        <tr><td>fecha_nacimiento</td><td>DATE</td><td class="ejemplo">1982-01-01</td></tr>
                        <tr><td>telefono</td><td>VARCHAR</td><td class="ejemplo">311-987-6543</td></tr>
                        <tr><td>es_tutor</td><td>BOOLEAN</td><td class="ejemplo">true / false</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Carreras -->
            <div class="tabla-bd">
                <h3>Carreras</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>nombre</td><td>VARCHAR</td><td class="ejemplo">Ingenieria en Alimentos</td></tr>
                        <tr><td>clave</td><td>VARCHAR</td><td class="ejemplo">IC</td></tr>
                        <tr><td>logo</td><td>VARCHAR</td><td class="ejemplo">/img/carreras/ing_alimentos.png</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Materias -->
            <div class="tabla-bd">
                <h3>Materias</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>nombre</td><td>VARCHAR</td><td class="ejemplo">Arquitectura de Software</td></tr>
                        <tr><td>carrera_id</td><td>INT</td><td class="ejemplo">1 (FK a carreras)</td></tr>
                        <tr><td>docente</td><td>VARCHAR</td><td class="ejemplo">Mtro. Lopez Hernandez</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Calificaciones -->
            <div class="tabla-bd">
                <h3>Calificaciones</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>alumno_id</td><td>INT</td><td class="ejemplo">1 (FK a alumnos)</td></tr>
                        <tr><td>materia_id</td><td>INT</td><td class="ejemplo">1 (FK a materias)</td></tr>
                        <tr><td>periodo</td><td>VARCHAR</td><td class="ejemplo">2024-1</td></tr>
                        <tr><td>calificacion</td><td>DECIMAL</td><td class="ejemplo">95.5</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Tutorias -->
            <div class="tabla-bd">
                <h3>Tutorias</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>alumno_id</td><td>INT</td><td class="ejemplo">1 (FK a alumnos)</td></tr>
                        <tr><td>maestro_id</td><td>INT</td><td class="ejemplo">1 (FK a maestros)</td></tr>
                        <tr><td>fecha</td><td>DATE</td><td class="ejemplo">2025-03-15</td></tr>
                        <tr><td>tema</td><td>VARCHAR</td><td class="ejemplo">Revision de calificaciones</td></tr>
                        <tr><td>notas</td><td>TEXT</td><td class="ejemplo">El alumno mejoro su rendimiento</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Tabla: Horarios -->
            <div class="tabla-bd">
                <h3>Horarios</h3>
                <table>
                    <thead>
                        <tr><th>Campo</th><th>Tipo</th><th>Ejemplo</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>id</td><td>INT</td><td class="ejemplo">1</td></tr>
                        <tr><td>grupo</td><td>VARCHAR</td><td class="ejemplo">8A</td></tr>
                        <tr><td>dia</td><td>VARCHAR</td><td class="ejemplo">Lunes</td></tr>
                        <tr><td>hora_inicio</td><td>TIME</td><td class="ejemplo">08:00:00</td></tr>
                        <tr><td>hora_fin</td><td>TIME</td><td class="ejemplo">10:00:00</td></tr>
                        <tr><td>materia_id</td><td>INT</td><td class="ejemplo">1 (FK a materias)</td></tr>
                        <tr><td>aula</td><td>VARCHAR</td><td class="ejemplo">Aula 301</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Relaciones basicas -->
            <div class="tabla-bd">
                <h3>Relaciones entre tablas</h3>
                <table>
                    <thead><tr><th>Relacion</th><th>Descripcion</th></tr></thead>
                    <tbody>
                        <tr><td>usuarios → alumnos</td><td>Un usuario puede ser un alumno</td></tr>
                        <tr><td>usuarios → maestros</td><td>Un usuario puede ser un maestro</td></tr>
                        <tr><td>alumnos → carreras</td><td>Un alumno pertenece a una carrera</td></tr>
                        <tr><td>maestros → carreras</td><td>Un maestro puede impartir varias carreras</td></tr>
                        <tr><td>materias → carreras</td><td>Una materia pertenece a una carrera</td></tr>
                        <tr><td>calificaciones → alumnos</td><td>Un alumno tiene muchas calificaciones</td></tr>
                        <tr><td>calificaciones → materias</td><td>Una materia tiene muchas calificaciones</td></tr>
                        <tr><td>tutorias → alumnos</td><td>Un alumno tiene muchas tutorias</td></tr>
                        <tr><td>tutorias → maestros</td><td>Un maestro tiene muchas tutorias</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <footer>
            <p>Sistema de Expedientes UTNay - Front End desarrollado para conexion con backend</p>
        </footer>
    </div>
</body>
</html>