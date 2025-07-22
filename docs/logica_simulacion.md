# Lógica Interna: Simulación Hipotecaria

Este documento explica en detalle cómo funciona la lógica de simulación de hipotecas en el proyecto.

## Flujo general
1. El usuario accede a `/simulations/create` y rellena el formulario con:
   - Precio de la vivienda
   - Importe del préstamo
   - Plazo en años
2. El sistema valida:
   - Que el usuario tenga perfil financiero completo
   - Que el préstamo no supere el 80% del valor de la vivienda
   - Que los valores estén dentro de los rangos permitidos
3. Se ejecuta la acción `CompareMortgagesAction`, que:
   - Recupera todos los productos hipotecarios activos de los bancos
   - Filtra los productos que cumplen con el importe, plazo y LTV
   - Calcula para cada producto:
     - Cuota mensual
     - Comisiones (apertura, estudio, cancelación)
     - TAE (Tasa Anual Equivalente)
     - Requisitos y productos vinculados
   - Devuelve un array de resultados ordenados por condiciones
4. Se guarda la simulación en la tabla `simulations` con un snapshot de los datos del usuario y los resultados.
5. El usuario es redirigido al detalle de la simulación, donde puede ver y comparar los productos.

## Ejemplo de resultado de simulación
```json
[
  {
    "bank": "Banco Ejemplo",
    "product": "Hipoteca Fija 25 años",
    "monthly_payment": 950.23,
    "fixed_interest_rate": 2.5,
    "opening_commission": 0.5,
    "tae": 2.7,
    "requirements": ["Nómina domiciliada", "Seguro hogar"],
    "linked_products": ["Tarjeta de crédito"],
    "is_active": true
  },
  ...
]
```

## Notas técnicas
- El cálculo de la cuota mensual se realiza con la fórmula estándar de préstamos franceses.
- El cálculo de TAE es aproximado y se puede personalizar en el método `getTaeAttribute()` del modelo `MortgageProduct`.
- Los resultados se almacenan como JSON en la columna `results` de la tabla `simulations`.

Para más detalles, consulta:
- `app/Actions/CompareMortgagesAction.php`
- `app/Models/MortgageProduct.php`
- `app/Models/Simulation.php`