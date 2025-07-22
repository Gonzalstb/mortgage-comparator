# Documentación Detallada: Mortgage Comparator

Bienvenido/a al proyecto Mortgage Comparator. Esta documentación está pensada para que cualquier persona nueva pueda entender, mantener y evolucionar el sistema de forma sencilla.

## Índice
- [Resumen del Proyecto](#resumen-del-proyecto)
- [Estructura del Proyecto](estructura.md)
- [Endpoints y Rutas](endpoints.md)
- [Lógica de Simulación](logica_simulacion.md)
- [Roles y Seguridad](roles_y_seguridad.md)
- [Testing y Buenas Prácticas](testing.md)
- [FAQ y Consejos](faq.md)

---

## Resumen del Proyecto

Mortgage Comparator es una aplicación web desarrollada en Laravel que permite a los usuarios comparar productos hipotecarios de diferentes bancos, realizar simulaciones personalizadas y gestionar su perfil financiero. Incluye un panel de administración para la gestión de bancos, productos y usuarios.

### Diagrama de Flujo de Usuario

```mermaid
flowchart TD
    A[Usuario] -->|Accede| B[Login/Registro]
    B -->|Autenticado| C[Dashboard]
    C --> D[Simulaciones]
    C --> E[Perfil]
    C --> F[Panel Admin]
    D -->|Crear| G[Formulario Simulación]
    G -->|Valida y Calcula| H[Comparador de Hipotecas]
    H -->|Resultados| I[Simulación Guardada]
    I -->|Ver| J[Detalle Simulación]
    I -->|Favorito| K[Marcar/Desmarcar Favorito]
    F --> L[Gestión Bancos]
    F --> M[Gestión Usuarios]
    L --> N[Productos Hipotecarios]
    N --> O[Crear/Editar Producto]
    M --> P[Ver/Editar Usuario]
```

### Diagrama de Clases (Modelo de Datos)

```mermaid
classDiagram
    class User {
        +int id
        +string name
        +string email
        +string password
        +UserRole role
        +profile()
        +simulations()
        +isAdmin()
    }
    class UserProfile {
        +int id
        +int user_id
        +int age
        +decimal net_income
        +ContractType contract_type
        +decimal available_savings
        +decimal monthly_expenses
        +int years_in_job
        +bool has_other_loans
        +user()
        +getDebtRatio()
    }
    class Simulation {
        +int id
        +int user_id
        +string reference_code
        +decimal property_price
        +decimal loan_amount
        +int term_years
        +array user_data
        +array results
        +bool is_favorite
        +datetime viewed_at
        +user()
        +getLtvAttribute()
        +markAsViewed()
        +toggleFavorite()
    }
    class Bank {
        +int id
        +string name
        +string slug
        +string logo_url
        +string website
        +string api_endpoint
        +array api_credentials
        +bool is_active
        +int priority
        +mortgageProducts()
        +activeProducts()
    }
    class MortgageProduct {
        +int id
        +int bank_id
        +string name
        +decimal min_amount
        +decimal max_amount
        +decimal max_ltv
        +decimal fixed_interest_rate
        +decimal variable_interest_rate
        +string variable_index
        +decimal differential
        +int min_term_years
        +int max_term_years
        +decimal opening_commission
        +decimal study_commission
        +decimal early_cancellation_fee
        +array requirements
        +array linked_products
        +bool is_active
        +bank()
        +getTaeAttribute()
    }
    User "1" -- "1" UserProfile
    User "1" -- "*" Simulation
    UserProfile "*" -- "1" User
    Simulation "*" -- "1" User
    Bank "1" -- "*" MortgageProduct
    MortgageProduct "*" -- "1" Bank
```

---

Para cualquier duda, consulta los archivos temáticos en la carpeta `docs/`.