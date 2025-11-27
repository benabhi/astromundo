# Sistema de Habilidades

## Concepto General

Las habilidades son la progresión principal del personaje. Determinan qué puede hacer, qué tan bien lo hace, y qué equipo puede usar.

---

## Estructura de Habilidades

### Niveles y Multiplicadores

```
Nivel 1: Base
Nivel 2: Base × Multiplicador
Nivel 3: Base × Multiplicador²
Nivel 4: Base × Multiplicador³
Nivel 5: Base × Multiplicador⁴
```

**Ejemplo con multiplicador x2:**
- Nivel 1: 100 XP
- Nivel 2: 200 XP (100 × 2)
- Nivel 3: 400 XP (100 × 4)
- Nivel 4: 800 XP (100 × 8)
- Nivel 5: 1,600 XP (100 × 16)

**Ejemplo con multiplicador x5:**
- Nivel 1: 100 XP
- Nivel 2: 500 XP
- Nivel 3: 2,500 XP
- Nivel 4: 12,500 XP
- Nivel 5: 62,500 XP

---

## Base de Datos

```sql
-- Catálogo de habilidades
CREATE TABLE skills (
    id BIGINT PRIMARY KEY,
    code VARCHAR(50) UNIQUE, -- 'PILOTING', 'MINING', 'TRADING'
    name VARCHAR(100),
    description TEXT,
    multiplier INT DEFAULT 1, -- 1-5
    base_xp INT DEFAULT 100,
    category VARCHAR(50), -- 'combat', 'industry', 'trade', 'science'
    icon VARCHAR(100),
    created_at TIMESTAMP
);

-- Dependencias entre habilidades
CREATE TABLE skill_dependencies (
    id BIGINT PRIMARY KEY,
    skill_id BIGINT, -- Habilidad que requiere
    required_skill_id BIGINT, -- Habilidad necesaria
    required_level INT, -- Nivel mínimo necesario
    
    FOREIGN KEY (skill_id) REFERENCES skills(id),
    FOREIGN KEY (required_skill_id) REFERENCES skills(id)
);

-- Progreso de habilidades del jugador
CREATE TABLE character_skills (
    id BIGINT PRIMARY KEY,
    character_id BIGINT,
    skill_id BIGINT,
    level INT DEFAULT 1,
    xp INT DEFAULT 0,
    injected_at TIMESTAMP,
    
    FOREIGN KEY (character_id) REFERENCES characters(id),
    FOREIGN KEY (skill_id) REFERENCES skills(id),
    UNIQUE KEY (character_id, skill_id)
);
```

---

## Categorías de Habilidades

### 1. Pilotaje (Piloting)

**Base para:** Todas las actividades espaciales

| Habilidad | Mult. | Dependencias | Beneficios |
|-----------|-------|--------------|------------|
| Pilotaje Básico | x1 | - | Naves T1 |
| Pilotaje Avanzado | x2 | Básico L3 | Naves T2, -10% combustible |
| Pilotaje de Combate | x3 | Avanzado L2 | Naves de combate, +15% maniobrabilidad |
| Pilotaje de Carga | x2 | Básico L2 | Cargueros, +20% capacidad |
| Navegación Estelar | x4 | Avanzado L4 | Viajes largos, -25% tiempo de viaje |

### 2. Industria (Industry)

**Base para:** Minería, fabricación, recolección

| Habilidad | Mult. | Dependencias | Beneficios |
|-----------|-------|--------------|------------|
| Minería Básica | x1 | - | Minería T1, +5% rendimiento |
| Minería Avanzada | x3 | Básica L3 | Minería T2, +15% rendimiento |
| Recolección de Gas | x2 | Minería L2 | Recolectar gases, +10% rendimiento |
| Refinado | x2 | Minería L2 | Refinar minerales, -10% pérdida |
| Fabricación | x4 | Refinado L3 | Fabricar componentes |

### 3. Comercio (Trade)

**Base para:** Economía, negociación

| Habilidad | Mult. | Dependencias | Beneficios |
|-----------|-------|--------------|------------|
| Comercio Básico | x1 | - | Acceso a mercados, -5% impuestos |
| Negociación | x2 | Comercio L2 | -10% precios compra, +10% precios venta |
| Análisis de Mercado | x3 | Negociación L2 | Ver tendencias, alertas de precios |
| Contrabando | x4 | Comercio L4 | Mercado negro, +50% ganancias (riesgo) |

### 4. Ciencia (Science)

**Base para:** Investigación, hacking, exploración

| Habilidad | Mult. | Dependencias | Beneficios |
|-----------|-------|--------------|------------|
| Ciencia Básica | x1 | - | Investigar en laboratorios |
| Escaneo Espacial | x2 | Ciencia L2 | Escanear asteroides/anomalías |
| Hacking | x4 | Ciencia L4 | Hackear sistemas, +bonus sigilo |
| Astrofísica | x5 | Ciencia L5, Escaneo L3 | Descubrir wormholes |

### 5. Combate (Combat)

**Base para:** PvP, PvE, defensa

| Habilidad | Mult. | Dependencias | Beneficios |
|-----------|-------|--------------|------------|
| Artillería Pequeña | x1 | - | Uso de armas pequeñas, +5% daño |
| Artillería Mediana | x2 | Pequeña L3 | Armas medianas, +10% daño |
| Artillería Grande | x3 | Mediana L3 | Armas grandes, +15% daño |
| Sistemas Defensivos | x2 | Pilotaje L2 | Escudos/armadura, +20% resistencia |
| Tácticas de Combate | x4 | Combat L4 | +25% efectividad en combate |

---

## Fórmulas de Progresión

### Cálculo de XP Requerida

```php
public function getRequiredXP(int $level, int $multiplier): int
{
    $baseXP = 100;
    return $baseXP * pow($multiplier, $level - 1);
}
```

**Tabla de referencia:**

| Nivel | x1 | x2 | x3 | x4 | x5 |
|-------|----|----|----|----|-----|
| 1 | 100 | 100 | 100 | 100 | 100 |
| 2 | 100 | 200 | 300 | 400 | 500 |
| 3 | 100 | 400 | 900 | 1,600 | 2,500 |
| 4 | 100 | 800 | 2,700 | 6,400 | 12,500 |
| 5 | 100 | 1,600 | 8,100 | 25,600 | 62,500 |
| **Total** | **500** | **3,100** | **12,100** | **34,100** | **78,100** |

### Otorgar XP

```php
public function grantSkillXP(string $skillCode, int $xp): void
{
    $characterSkill = $this->character->skills()
        ->whereHas('skill', fn($q) => $q->where('code', $skillCode))
        ->first();
    
    if (!$characterSkill) {
        return; // No tiene la habilidad
    }
    
    $characterSkill->xp += $xp;
    
    // Verificar si sube de nivel
    while ($this->canLevelUp($characterSkill)) {
        $characterSkill->level++;
        $characterSkill->xp -= $this->getRequiredXP(
            $characterSkill->level,
            $characterSkill->skill->multiplier
        );
        
        // Notificar level up
        $this->character->user->notify(
            new SkillLevelUp($characterSkill->skill, $characterSkill->level)
        );
    }
    
    $characterSkill->save();
}

private function canLevelUp(CharacterSkill $characterSkill): bool
{
    if ($characterSkill->level >= 5) {
        return false; // Nivel máximo
    }
    
    $requiredXP = $this->getRequiredXP(
        $characterSkill->level + 1,
        $characterSkill->skill->multiplier
    );
    
    return $characterSkill->xp >= $requiredXP;
}
```

---

## Sistema de Inyección en Laboratorios

### Concepto

Los jugadores deben "inyectar" habilidades en laboratorios (módulos de estación) pagando créditos.

### Base de Datos

```sql
-- Habilidades disponibles en laboratorios
CREATE TABLE laboratory_skills (
    id BIGINT PRIMARY KEY,
    station_module_id BIGINT, -- ID del laboratorio
    skill_id BIGINT,
    injection_cost INT, -- Créditos
    
    FOREIGN KEY (station_module_id) REFERENCES station_modules(id),
    FOREIGN KEY (skill_id) REFERENCES skills(id)
);
```

### Proceso de Inyección

```php
public function injectSkill(Request $request, StationModule $laboratory, Skill $skill)
{
    $character = auth()->user()->character;
    
    // Validar que está en la estación
    if ($character->station_id !== $laboratory->station_id) {
        return back()->with('error', 'No estás en esta estación');
    }
    
    // Validar que es un laboratorio
    if ($laboratory->type !== 'laboratory') {
        return back()->with('error', 'Este no es un laboratorio');
    }
    
    // Validar que el laboratorio tiene la habilidad
    if (!$laboratory->skills()->where('skill_id', $skill->id)->exists()) {
        return back()->with('error', 'Esta habilidad no está disponible aquí');
    }
    
    // Validar que no la tiene ya
    if ($character->skills()->where('skill_id', $skill->id)->exists()) {
        return back()->with('error', 'Ya tienes esta habilidad');
    }
    
    // Validar dependencias
    foreach ($skill->dependencies as $dependency) {
        $hasSkill = $character->skills()
            ->where('skill_id', $dependency->required_skill_id)
            ->where('level', '>=', $dependency->required_level)
            ->exists();
        
        if (!$hasSkill) {
            return back()->with('error', 'No cumples los requisitos');
        }
    }
    
    // Validar créditos
    $cost = $laboratory->skills()
        ->where('skill_id', $skill->id)
        ->first()
        ->injection_cost;
    
    if ($character->user->credits < $cost) {
        return back()->with('error', 'Créditos insuficientes');
    }
    
    // Inyectar
    DB::transaction(function() use ($character, $skill, $cost) {
        $character->skills()->create([
            'skill_id' => $skill->id,
            'level' => 1,
            'xp' => 0,
            'injected_at' => now(),
        ]);
        
        $character->user->decrement('credits', $cost);
    });
    
    return back()->with('success', 'Habilidad inyectada exitosamente');
}
```

---

## Roles Iniciales

### Minero

**Habilidades iniciales:**
- Pilotaje Básico (L1)
- Minería Básica (L1)

**Nave inicial:** Prospector-class (nave minera básica)

### Transportista

**Habilidades iniciales:**
- Pilotaje Básico (L1)
- Pilotaje de Carga (L1)
- Comercio Básico (L1)

**Nave inicial:** Hauler-class (carguero pequeño)

### Cazador

**Habilidades iniciales:**
- Pilotaje Básico (L1)
- Pilotaje de Combate (L1)
- Artillería Pequeña (L1)

**Nave inicial:** Interceptor-class (caza ligero)

---

## UI para Visualizar Habilidades

### Vista de Personaje

```
┌─────────────────────────────────────┐
│ PERFIL DEL PILOTO                  │
├─────────────────────────────────────┤
│ Nombre: Juan Pérez                 │
│ Créditos: 15,000 ₡                 │
│ Energía: 85/100                    │
│                                     │
│ ACCIÓN ACTUAL:                     │
│ [████████░░] Minería (80%)         │
│ Tiempo restante: 12 minutos        │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│ HABILIDADES                        │
├─────────────────────────────────────┤
│ ▶ Pilotaje                         │
│   • Básico L3 [████░] 75%          │
│   • Carga L1 [█░░░░] 10%           │
│                                     │
│ ▶ Industria                        │
│   • Minería Básica L2 [███░] 60%   │
│   • Refinado L1 [░░░░░] 0% (LOCKED)│
│                                     │
│ ▶ Comercio                         │
│   • Básico L1 [██░░░] 40%          │
└─────────────────────────────────────┘
```

---

## Próximos Pasos

1. Crear seeders para habilidades base
2. Implementar UI de laboratorio
3. Sistema de validación de dependencias
4. UI de perfil del piloto
5. Notificaciones de level up
