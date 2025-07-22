# Testing y Buenas Prácticas

Este documento describe cómo se realizan las pruebas en el proyecto y consejos para mantener la calidad del código.

## Tipos de pruebas
- **Unitarias:** Prueban métodos y lógica de negocio aislada (ej: servicios, modelos).
- **Funcionales:** Prueban flujos completos de usuario (ej: login, simulación, favoritos).
- **Integración:** Prueban la interacción entre varios componentes (ej: simulación + guardado en base de datos).

## Ubicación de los tests
- `tests/Unit/` — Pruebas unitarias (ej: MortgageCalculatorServiceTest.php, RecommendationServiceTest.php)
- `tests/Feature/` — Pruebas funcionales (ej: MortgageSimulationTest.php, Auth/AuthenticationTest.php)

## Ejemplo de test funcional
```php
test('un usuario puede crear una simulación', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->post('/simulations/calculate', [
            'property_price' => 200000,
            'loan_amount' => 150000,
            'term_years' => 20,
        ])
        ->assertRedirect();
    $this->assertDatabaseHas('simulations', [
        'user_id' => $user->id,
        'loan_amount' => 150000,
    ]);
});
```

## Buenas prácticas
- Escribe tests para cada nueva funcionalidad.
- Usa factories para crear datos de prueba.
- Ejecuta los tests antes de cada despliegue: `php artisan test`
- Mantén el código limpio y documentado.

## Cobertura
- El proyecto cubre autenticación, simulaciones, perfil y servicios principales.
- Puedes ampliar la cobertura añadiendo tests para nuevos controladores, servicios o reglas de negocio.

---

Para dudas sobre testing, consulta los archivos en `tests/` o la documentación oficial de Laravel.