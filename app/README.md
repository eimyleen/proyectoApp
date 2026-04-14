# Sistema de Expedientes UTNay

Plataforma web para la gestión de expedientes académicos de la Universidad Tecnológica de Nayarit.

## Descripción del proyecto

Actualmente, la gestión de expedientes de alumnos en la Universidad Tecnológica de Nayarit se realiza de forma poco eficiente, lo que puede provocar pérdida de información, desorganización y falta de seguridad en los datos.

### Objetivo general

Desarrollar una plataforma web que recopile, organice y gestione los expedientes de los alumnos de manera segura, eficiente y accesible.

### Objetivos específicos

- Centralizar la información en una base de datos
- Garantizar la seguridad e integridad de los datos
- Facilitar el acceso a la información según el rol del usuario
- Mejorar la organización y consulta de expedientes

## Tecnologías utilizadas

| Capa | Tecnologías |
|------|-------------|
| **Frontend** | HTML, CSS, JavaScript, Blade (Laravel) |
| **Backend** | PHP, Laravel |
| **Base de datos** | MySQL |
| **Autenticación** | Sistema propio con Laravel |
| **Control de versiones** | Git, GitHub |

## Roles de usuario

### Administrador
- Gestión completa de usuarios (alumnos y maestros)
- Acceso total al sistema
- Crear, modificar y eliminar expedientes

### Maestro
- Consulta expedientes de alumnos
- Registra calificaciones y observaciones
- Actualiza datos de sus alumnos

### Alumno
- Consulta su propio expediente
- Visualiza calificaciones y datos personales
- No puede modificar información sensible

## Instalación

```bash
git clone https://github.com/eimyleen/proyectoApp.git
cd proyectoApp
composer install
cp .env.example .env
php artisan key:generate
php artisan serve