# Sistema de Ticks y Acciones

## Concepto General

El servidor ejecuta **ticks** periódicos que actualizan el estado del juego. Las acciones de los jugadores consumen ticks para completarse.

---

## Propuesta de Sistema de Ticks

### Opción 1: Ticks Rápidos (Recomendado) ⭐

**Configuración:**
- **Tick Duration:** 5 minutos
- **Ticks por día:** 288 ticks (24h × 12 ticks/hora)
- **Actualización:** Cron job cada 5 minutos

**Ventajas:**
- ✅ Sensación de progreso constante
- ✅ Acciones cortas (exploración, comunicación) son viables
- ✅ Permite acciones de diferentes duraciones
- ✅ Más flexible para balanceo

**Desventajas:**
- ❌ Más carga en servidor (mitigable con optimización)

**Ejemplo de Acciones:**
- Descansar: 6 ticks (30 min) → +10 energía
- Minar (básico): 12 ticks (1 hora) → 1 unidad de mineral
- Viajar sistema cercano: 24 ticks (2 horas)
- Investigar habilidad: 144 ticks (12 horas)

---

### Opción 2: Ticks Lentos (Estilo Popmundo)

**Configuración:**
- **Tick Duration:** 12 horas
- **Ticks por día:** 2 ticks
- **Actualización:** Cron job a las 00:00 y 12:00 UTC

**Ventajas:**
- ✅ Menos carga en servidor
- ✅ Fomenta planificación estratégica
- ✅ Jugadores revisan 2 veces al día

**Desventajas:**
- ❌ Muy lento para acciones pequeñas
- ❌ Menos engaging para jugadores activos
- ❌ Difícil balancear acciones cortas vs largas

---

### Opción 3: Sistema Híbrido (Innovador) 🚀

**Configuración:**
- **Micro-ticks:** 1 minuto (para acciones inmediatas)
- **Macro-ticks:** 6 horas (para acciones largas)
- **Actualización:** Cron cada minuto + batch cada 6h

**Tipos de Acciones:**
- **Inmediatas (0 ticks):** Navegar UI, chatear, cambiar módulo
- **Cortas (micro-ticks):** 5-60 ticks (5min-1h) → Descanso, comercio simple
- **Medias (macro-ticks):** 1-4 ticks (6h-24h) → Minería, investigación
- **Largas (macro-ticks):** 5+ ticks (30h+) → Construcción, fabricación compleja

**Ventajas:**
- ✅ Lo mejor de ambos mundos
- ✅ Jugadores activos tienen qué hacer
- ✅ Jugadores casuales pueden progresar con acciones largas

**Desventajas:**
- ❌ Más complejo de implementar
- ❌ Requiere dos sistemas de procesamiento

---

## Arquitectura Técnica

### Base de Datos

```sql
-- Tabla de acciones en curso
CREATE TABLE player_actions (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    action_type VARCHAR(50), -- 'mining', 'training', 'traveling'
    started_at TIMESTAMP,
    duration_ticks INT, -- Ticks necesarios
    ticks_elapsed INT DEFAULT 0,
    progress DECIMAL(5,2) DEFAULT 0, -- Porcentaje 0-100
    data JSON, -- Parámetros específicos de la acción
    status VARCHAR(20) DEFAULT 'in_progress', -- 'in_progress', 'completed', 'cancelled'
    
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Índices
CREATE INDEX idx_player_actions_status ON player_actions(status);
CREATE INDEX idx_player_actions_user ON player_actions(user_id);
```

### Sistema de Procesamiento

```php
// Comando Laravel para procesar ticks
php artisan game:process-tick

// Flujo:
1. Incrementar contador global de ticks
2. Para cada acción activa:
   - Incrementar ticks_elapsed
   - Calcular progress = (ticks_elapsed / duration_ticks) * 100
   - Si progress >= 100:
     - Ejecutar lógica de completado
     - Otorgar recompensas/experiencia
     - Marcar como completed
3. Limpiar acciones completadas antiguas
4. Enviar notificaciones a jugadores
```

### Cálculo Dinámico de Duración

```php
class ActionCalculator
{
    public function calculateDuration(Action $action, User $user): int
    {
        $baseTicks = $action->base_duration_ticks;
        
        // Modificadores por habilidades
        $skillBonus = $this->getSkillBonus($user, $action->required_skill);
        
        // Modificadores por equipamiento (nave, módulos)
        $equipmentBonus = $this->getEquipmentBonus($user, $action);
        
        // Fórmula final
        $finalTicks = $baseTicks * (1 - $skillBonus) * (1 - $equipmentBonus);
        
        return max(1, ceil($finalTicks)); // Mínimo 1 tick
    }
    
    private function getSkillBonus(User $user, Skill $skill): float
    {
        $level = $user->getSkillLevel($skill->id);
        // Nivel 1: 0%, Nivel 2: 10%, Nivel 3: 20%, Nivel 4: 30%, Nivel 5: 40%
        return ($level - 1) * 0.10;
    }
}
```

---

## Ejemplo Completo: Minería

### Configuración

```php
// config/actions.php
'mining' => [
    'base_duration_ticks' => 12, // 1 hora con ticks de 5min
    'required_skill' => 'mining',
    'required_equipment' => ['mining_ship', 'cargo_module', 'mining_laser'],
    'min_skill_level' => 1,
    'energy_cost' => 10,
    'xp_reward' => 50,
],
```

### Inicio de Acción

```php
public function startMining(Request $request)
{
    $user = auth()->user();
    
    // Validaciones
    if (!$user->hasRequiredSkill('mining', 1)) {
        return back()->with('error', 'Habilidad de minería insuficiente');
    }
    
    if ($user->character->energy < 10) {
        return back()->with('error', 'Energía insuficiente');
    }
    
    // Calcular duración
    $duration = app(ActionCalculator::class)
        ->calculateDuration(Action::mining(), $user);
    
    // Crear acción
    PlayerAction::create([
        'user_id' => $user->id,
        'action_type' => 'mining',
        'started_at' => now(),
        'duration_ticks' => $duration,
        'data' => [
            'asteroid_field_id' => $request->asteroid_field_id,
            'expected_yield' => $this->calculateYield($user),
        ],
    ]);
    
    // Consumir energía
    $user->character->decrement('energy', 10);
    
    return redirect()->route('dashboard')
        ->with('success', 'Minería iniciada');
}
```

### Procesamiento en Tick

```php
public function handle()
{
    PlayerAction::where('status', 'in_progress')
        ->chunk(100, function ($actions) {
            foreach ($actions as $action) {
                $action->increment('ticks_elapsed');
                $action->progress = ($action->ticks_elapsed / $action->duration_ticks) * 100;
                $action->save();
                
                if ($action->progress >= 100) {
                    $this->completeAction($action);
                }
            }
        });
}

private function completeAction(PlayerAction $action)
{
    switch ($action->action_type) {
        case 'mining':
            $this->completeMining($action);
            break;
        // ... otros tipos
    }
    
    $action->update(['status' => 'completed']);
}

private function completeMining(PlayerAction $action)
{
    $user = $action->user;
    $yield = $action->data['expected_yield'];
    
    // Otorgar recursos
    $user->inventory()->create([
        'resource_type' => 'iron_ore',
        'quantity' => $yield,
    ]);
    
    // Otorgar XP
    $user->grantSkillXP('mining', 50);
    
    // Notificación
    $user->notify(new MiningCompleted($yield));
}
```

---

## Recomendación Final

**Empezar con Opción 1 (Ticks de 5 minutos):**
- Más flexible para iterar
- Fácil ajustar después
- Mejor experiencia de usuario
- Permite probar diferentes duraciones

**En el futuro, migrar a Opción 3 (Híbrido) si es necesario.**

---

## Próximos Pasos

1. Decidir frecuencia de tick
2. Crear tabla `player_actions`
3. Implementar comando `game:process-tick`
4. Crear UI para mostrar acciones en progreso
5. Implementar sistema de notificaciones
