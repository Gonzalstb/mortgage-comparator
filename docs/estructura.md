# Estructura del Proyecto

Esta guía describe la organización de carpetas y archivos del proyecto Mortgage Comparator para que cualquier desarrollador pueda ubicarse rápidamente.

## Raíz del proyecto
- `app/` — Código principal de la aplicación (modelos, controladores, servicios, repositorios, enums, etc.)
- `routes/` — Definición de rutas web y de autenticación.
- `resources/views/` — Vistas Blade (frontend).
- `database/` — Migraciones, seeders y factories para la base de datos.
- `public/` — Archivos públicos (index.php, favicon, etc.)
- `config/` — Archivos de configuración de Laravel y servicios.
- `tests/` — Pruebas unitarias y funcionales.
- `composer.json` — Dependencias PHP y scripts.
- `package.json` — Dependencias y scripts de frontend.

## app/
- `Models/` — Modelos Eloquent (User, Bank, MortgageProduct, Simulation, UserProfile).
- `Http/Controllers/` — Controladores HTTP (incluye subcarpetas Auth y Admin).
- `Actions/` — Acciones de negocio (ej: CompareMortgagesAction).
- `Services/` — Servicios de lógica de negocio (ej: MortgageCalculatorService).
- `Repositories/` — Abstracción para acceso a datos.
- `Enums/` — Enumeraciones para roles, tipos de contrato, etc.
- `View/Components/` — Componentes Blade reutilizables.

## resources/views/
- `auth/` — Vistas de autenticación (login, registro, recuperación, etc.)
- `dashboard.blade.php` — Panel principal del usuario.
- `admin/` — Vistas de administración (bancos, usuarios, productos).
- `simulations/` — Vistas para simulaciones hipotecarias.
- `profile/` — Edición y gestión del perfil de usuario.
- `layouts/` — Plantillas base (app, guest, navigation).
- `components/` — Componentes visuales reutilizables.

## routes/
- `web.php` — Rutas principales de la aplicación.
- `auth.php` — Rutas de autenticación.

## database/
- `migrations/` — Migraciones para crear y modificar tablas.
- `seeders/` — Carga de datos iniciales.
- `factories/` — Factories para tests.

---

Para cualquier duda sobre la ubicación de un archivo o carpeta, consulta este documento primero.