# Arquitectura General del Juego

## Stack Tecnológico

### Backend
- **Framework:** Laravel 11.x
- **Database:** MySQL 8.0
- **Cache:** Redis
- **Queue:** Laravel Queue (Redis driver)
- **Cron:** Laravel Scheduler

### Frontend
- **Views:** Blade (Server-side rendering)
- **CSS:** Tailwind CSS
- **JS:** Alpine.js (minimal interactivity)
- **No SPA:** Todo server-side para SEO y simplicidad

---

## Estructura de Directorios Propuesta

```
astromundo/
├── src/
│   ├── app/
│   │   ├── Console/
│   │   │   └── Commands/
│   │   │       ├── ProcessGameTick.php
│   │   │       └── CleanupCompletedActions.php
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       ├── Character/
│   │   │       │   ├── SkillController.php
│   │   │       │   ├── ProfileController.php
│   │   │       │   └── ActionController.php
│   │   │       ├── Station/
│   │   │       │   ├── StationController.php
│   │   │       │   ├── ModuleController.php
│   │   │       │   └── LaboratoryController.php
│   │   │       ├── Ship/
│   │   │       │   ├── ShipController.php
│   │   │       │   └── TravelController.php
│   │   │       ├── Industry/
│   │   │       │   ├── MiningController.php
│   │   │       │   └── RefiningController.php
│   │   │       └── Economy/
│   │   │           ├── MarketController.php
│   │   │           └── TradeController.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Character.php
│   │   │   ├── Skill.php
│   │   │   ├── CharacterSkill.php
│   │   │   ├── PlayerAction.php
│   │   │   ├── Ship.php
│   │   │   ├── Station.php
│   │   │   └── ... (otros modelos)
│   │   ├── Services/
│   │   │   ├── ActionService.php
│   │   │   ├── SkillService.php
│   │   │   ├── TravelService.php
│   │   │   └── TickProcessor.php
│   │   ├── Jobs/
│   │   │   ├── ProcessPlayerAction.php
│   │   │   └── NotifyActionComplete.php
│   │   └── Events/
│   │       ├── SkillLevelUp.php
│   │       ├── ActionCompleted.php
│   │       └── TravelArrived.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── SkillSeeder.php
│   │       ├── SkillDependencySeeder.php
│   │       └── StarterShipSeeder.php
│   └── resources/
│       └── views/
│           ├── character/
│           │   ├── profile.blade.php
│           │   ├── skills.blade.php
│           │   └── actions.blade.php
│           ├── station/
│           │   ├── index.blade.php
│           │   ├── module.blade.php
│           │   └── laboratory.blade.php
│           └── ship/
│               └── cockpit.blade.php
├── docs/
│   ├── 01-tick-system.md
│   ├── 02-skill-system.md
│   ├── 03-architecture.md (este archivo)
│   ├── 04-game-economy.md
│   ├── 05-ship-equipment.md
│   ├── 06-ui-ux-design.md
│   └── 99-roadmap.md
└── README.md
```

---

## Capas de la Aplicación

### 1. Controllers (Capa de Presentación)
- Maneja requests HTTP
- Valida input
- Delega lógica a Services
- Retorna vistas

### 2. Services (Capa de Lógica de Negocio)
- Lógica compleja de juego
- Cálculos (XP, duración, recompensas)
- Orquestación de múltiples modelos
- **Reutilizable** entre controllers y commands

### 3. Models (Capa de Datos)
- Representa entidades de BD
- Relaciones Eloquent
- Getters/setters simples
- **NO** lógica de negocio compleja

### 4. Jobs (Tareas Asíncronas)
- Procesamiento en background
- Acciones de larga duración
- Notificaciones

### 5. Events/Listeners (Eventos del Sistema)
- Desacoplar componentes
- Reaccionar a cambios de estado
- Logs, notificaciones, achievements

---

## Patrones de Diseño Recomendados

### 1. Service Pattern

```php
// app/Services/ActionService.php
class ActionService
{
    public function startAction(User $user, string $type, array $params): PlayerAction
    {
        // Validar
        $this->validateAction($user, $type, $params);
        
        // Calcular duración
        $duration = $this->calculateDuration($user, $type);
        
        // Consumir recursos
        $this->consumeResources($user, $type);
        
        // Crear acción
        return PlayerAction::create([
            'user_id' => $user->id,
            'action_type' => $type,
            'duration_ticks' => $duration,
            'data' => $params,
        ]);
    }
}

// Uso en controller
public function startMining(Request $request, ActionService $actionService)
{
    $action = $actionService->startAction(
        auth()->user(),
        'mining',
        ['asteroid_id' => $request->asteroid_id]
    );
    
    return redirect()->route('dashboard');
}
```

### 2. Repository Pattern (Opcional, para queries complejas)

```php
// app/Repositories/SkillRepository.php
class SkillRepository
{
    public function getAvailableForInjection(Character $character): Collection
    {
        return Skill::whereNotIn('id', $character->skills()->pluck('skill_id'))
            ->with('dependencies')
            ->get()
            ->filter(function($skill) use ($character) {
                return $this->meetsRequirements($character, $skill);
            });
    }
    
    private function meetsRequirements(Character $character, Skill $skill): bool
    {
        foreach ($skill->dependencies as $dep) {
            $hasSkill = $character->skills()
                ->where('skill_id', $dep->required_skill_id)
                ->where('level', '>=', $dep->required_level)
                ->exists();
            
            if (!$hasSkill) return false;
        }
        
        return true;
    }
}
```

### 3. Strategy Pattern (Para diferentes tipos de acciones)

```php
// app/Actions/Contracts/ActionHandler.php
interface ActionHandler
{
    public function handle(PlayerAction $action): void;
    public function validate(User $user, array $params): bool;
    public function calculateDuration(User $user, array $params): int;
}

// app/Actions/MiningActionHandler.php
class MiningActionHandler implements ActionHandler
{
    public function handle(PlayerAction $action): void
    {
        $yield = $action->data['expected_yield'];
        $user = $action->user;
        
        // Otorgar recursos
        $user->inventory()->create([
            'resource_type' => 'iron_ore',
            'quantity' => $yield,
        ]);
        
        // XP
        app(SkillService::class)->grantXP($user, 'mining', 50);
    }
    
    // ... otros métodos
}

// Registro en ActionService
private array $handlers = [
    'mining' => MiningActionHandler::class,
    'trading' => TradingActionHandler::class,
    'traveling' => TravelActionHandler::class,
];
```

---

## Sistema de Colas (Queues)

### Configuración

```php
// config/queue.php
'connections' => [
    'redis' => [
        'driver' => 'redis',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

### Uso para Acciones

```php
// Encolar procesamiento de tick
dispatch(new ProcessPlayerAction($action->id))
    ->delay(now()->addMinutes(5));

// Job
class ProcessPlayerAction implements ShouldQueue
{
    public function handle()
    {
        $action = PlayerAction::find($this->actionId);
        
        if (!$action || $action->status !== 'in_progress') {
            return;
        }
        
        $action->increment('ticks_elapsed');
        
        if ($action->ticks_elapsed >= $action->duration_ticks) {
            app(ActionService::class)->completeAction($action);
        }
    }
}
```

---

## Scheduler (Cron Jobs)

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Procesar tick cada 5 minutos
    $schedule->command('game:process-tick')
             ->everyFiveMinutes()
             ->withoutOverlapping();
    
    // Limpiar acciones completadas cada hora
    $schedule->command('game:cleanup-actions')
             ->hourly();
    
    // Backup diario
    $schedule->command('backup:run')
             ->daily()
             ->at('02:00');
}
```

---

## Optimizaciones

### 1. Eager Loading

```php
// ❌ N+1 Problem
$characters = Character::all();
foreach ($characters as $character) {
    echo $character->skills->count(); // Query por cada character
}

// ✅ Solución
$characters = Character::with('skills')->get();
foreach ($characters as $character) {
    echo $character->skills->count(); // Sin queries extra
}
```

### 2. Chunking para Grandes Datasets

```php
// Procesar acciones en lotes
PlayerAction::where('status', 'in_progress')
    ->chunk(100, function($actions) {
        foreach ($actions as $action) {
            $this->processAction($action);
        }
    });
```

### 3. Caching

```php
// Cache de habilidades (raramente cambian)
$skills = Cache::remember('skills.all', 3600, function() {
    return Skill::with('dependencies')->get();
});

// Cache de perfil de usuario
$profile = Cache::remember("user.{$userId}.profile", 600, function() use ($userId) {
    return User::with(['character.skills', 'currentAction'])->find($userId);
});
```

---

## Seguridad

### Validaciones de Acciones

```php
// Siempre validar permisos
if ($character->station_id !== $laboratory->station_id) {
    abort(403, 'No estás en esta estación');
}

// Validar propiedad
if ($ship->user_id !== auth()->id()) {
    abort(403, 'Esta nave no te pertenece');
}

// Rate limiting
RateLimiter::attempt(
    'action:' . auth()->id(),
    $perMinute = 10,
    function() {
        // Ejecutar acción
    }
);
```

---

## Testing

### Unit Tests

```php
// tests/Unit/SkillServiceTest.php
public function test_grants_xp_correctly()
{
    $user = User::factory()->create();
    $skill = Skill::factory()->create(['multiplier' => 2]);
    $user->character->skills()->attach($skill, ['level' => 1, 'xp' => 0]);
    
    app(SkillService::class)->grantXP($user, $skill->code, 150);
    
    $characterSkill = $user->character->skills->first();
    $this->assertEquals(2, $characterSkill->pivot->level);
}
```

### Feature Tests

```php
// tests/Feature/MiningTest.php
public function test_user_can_start_mining()
{
    $user = $this->actingAs(User::factory()->create());
    
    $response = $user->post('/mining/start', [
        'asteroid_id' => 1,
    ]);
    
    $response->assertRedirect()->assertSessionHas('success');
    $this->assertDatabaseHas('player_actions', [
        'user_id' => $user->id,
        'action_type' => 'mining',
    ]);
}
```

---

## Próximos Pasos

1. Definir todas las tablas de BD
2. Crear migrations completas
3. Seeders para data inicial
4. Implementar TickProcessor
5. UI base para acciones
6. Sistema de navegación del juego
