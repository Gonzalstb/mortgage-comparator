# Endpoints y Rutas Principales

Esta guía describe los endpoints más importantes del proyecto Mortgage Comparator, su propósito y ejemplos de uso.

## Rutas públicas
- `/` — Página de bienvenida.

## Autenticación
- `GET /login` — Muestra el formulario de login.
- `POST /login` — Procesa el login.
- `GET /register` — Formulario de registro.
- `POST /register` — Procesa el registro.
- `POST /logout` — Cierra sesión.
- `GET /forgot-password` — Solicita recuperación de contraseña.
- `POST /forgot-password` — Envía email de recuperación.
- `GET /reset-password/{token}` — Formulario para nueva contraseña.
- `POST /reset-password` — Procesa el cambio de contraseña.

## Rutas autenticadas
- `GET /dashboard` — Panel principal del usuario.
- `GET /profile` — Editar perfil.
- `PUT /profile` — Actualizar perfil.

### Simulaciones
- `GET /simulations` — Listado de simulaciones del usuario.
- `GET /simulations/create` — Formulario para nueva simulación.
- `POST /simulations/calculate` — Calcula y guarda una simulación.
  - **Body:**
    ```json
    {
      "property_price": 250000,
      "loan_amount": 200000,
      "term_years": 25
    }
    ```
- `GET /simulations/{reference}` — Ver detalle de una simulación.
- `POST /simulations/{reference}/favorite` — Marcar/desmarcar como favorito.
- `DELETE /simulations/{reference}` — Eliminar simulación.

## Rutas de administración (requieren rol admin)
- `GET /admin/users` — Listado de usuarios.
- `GET /admin/users/{user}` — Detalle de usuario.
- `GET /admin/banks` — Listado de bancos.
- `POST /admin/banks` — Crear banco.
- `PUT /admin/banks/{bank}` — Editar banco.
- `DELETE /admin/banks/{bank}` — Eliminar banco.
- `POST /admin/banks/{bank}/toggle` — Activar/desactivar banco.
- `GET /admin/banks/{bank}/products/create` — Formulario para producto hipotecario.
- `POST /admin/banks/{bank}/products` — Crear producto hipotecario.

---

Para detalles de parámetros, respuestas y ejemplos ampliados, consulta los controladores correspondientes en `app/Http/Controllers/`.