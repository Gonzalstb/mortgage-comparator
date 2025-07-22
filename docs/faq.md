# FAQ y Consejos para Nuevos Desarrolladores

## ¿Por dónde empiezo?
1. Lee el archivo `README.md` de la carpeta `docs` para entender la visión global.
2. Consulta `estructura.md` para ubicarte en el proyecto.
3. Revisa los endpoints en `endpoints.md` y prueba el flujo de usuario.
4. Si vas a trabajar en simulaciones, lee `logica_simulacion.md`.
5. Si vas a tocar seguridad o roles, revisa `roles_y_seguridad.md`.
6. Antes de hacer cambios, ejecuta los tests (`php artisan test`).

## ¿Cómo añado una nueva funcionalidad?
- Crea un controlador o servicio en `app/`.
- Añade la ruta correspondiente en `routes/web.php` o `routes/auth.php`.
- Si es necesario, crea una migración para la base de datos.
- Añade tests en `tests/Feature/` o `tests/Unit/`.
- Documenta tu endpoint en `docs/endpoints.md` si es relevante.

## ¿Cómo gestiono los roles?
- Usa el enum `UserRole` y el middleware `IsAdmin` para restringir acceso.
- Si creas nuevas rutas de administración, protégelas con el middleware `admin`.

## ¿Cómo ejecuto los tests?
- Ejecuta `php artisan test` en la raíz del proyecto.
- Puedes filtrar por archivo o método usando las opciones de PHPUnit.

## ¿Dónde están los estilos y scripts?
- Estilos: `resources/css/` y `resources/sass/`
- Scripts JS: `resources/js/`
- Configuración de Tailwind: `tailwind.config.js`
- Configuración de Vite: `vite.config.js`

## ¿Dónde están los datos de ejemplo?
- En los seeders de `database/seeders/`.
- Puedes ejecutar `php artisan db:seed` para poblar la base de datos de desarrollo.

## ¿A quién pregunto si tengo dudas?
- Consulta primero la documentación en `docs/`.
- Si no encuentras respuesta, pregunta a un desarrollador senior del equipo.

---

¡Bienvenido/a al proyecto! La documentación está pensada para que puedas avanzar de forma autónoma y segura.