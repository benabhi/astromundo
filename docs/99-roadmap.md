# Roadmap de Desarrollo - VaxaV

## Fase Actual: Foundation (Base del Juego)

### ✅ Completado

- [x] Autenticación y registro
- [x] Sistema de estaciones y módulos
- [x] Navegación básica (breadcrumb, vistas)
- [x] Sistema de ubicación (current_location_type/id)
- [x] Campos de acción (current_action, action_started_at)
- [x] Modal de desatraque
- [x] UI tech/sci-fi básica

---

## 🎯 Fase 1: Core Systems (3-4 semanas)

**Objetivo:** Implementar los sistemas fundamentales que habilitan todo lo demás.

### 1.1 Sistema de Habilidades (Prioridad: CRÍTICA) 🔴

**Por qué primero:** Sin habilidades no hay progresión, y muchos sistemas dependen de esto.

**Tareas:**
- [ ] Crear migration para `skills`, `skill_dependencies`, `character_skills`
- [ ] Crear modelos `Skill`, `SkillDependency`, `CharacterSkill`
- [ ] Seeder para habilidades base (10-15 habilidades iniciales)
- [ ] Seeder para dependencias
- [ ] Service `SkillService` con lógica de XP y level-up
- [ ] Actualizar `CharacterCreationController` para asignar skills según rol
- [ ] Vista básica de habilidades del personaje

**Tiempo estimado:** 1 semana

**Resultado:** Los jugadores tienen habilidades desde el inicio.

---

### 1.2 Sistema de Acciones (Prioridad: CRÍTICA) 🔴

**Por qué segundo:** Es el núcleo del gameplay.

**Tareas:**
- [ ] Migration para `player_actions`
- [ ] Modelo `PlayerAction`
- [ ] Service `ActionService` con métodos:
  - `startAction()`
  - `cancelAction()`
  - `completeAction()`
  - `calculateDuration()`
- [ ] Implementar primera acción simple: **"Descansar"**
  - No requiere habilidades
  - Duración: 6 ticks (30 min)
  - Resultado: +10 energía
- [ ] UI para ver acción actual (en dashboard o perfil)

**Tiempo estimado:** 1 semana

**Resultado:** Jugadores pueden ejecutar su primera acción.

---

### 1.3 Procesador de Ticks (Prioridad: ALTA) 🟡

**Por qué tercero:** Hace que las acciones progresen.

**Tareas:**
- [ ] Comando `game:process-tick`
- [ ] Service `TickProcessor`
- [ ] Configurar Laravel Scheduler (cada 5 min)
- [ ] Implementar lógica de incremento de `ticks_elapsed`
- [ ] Auto-completar acciones cuando `progress >= 100`
- [ ] Sistema de notificaciones (al menos en BD)

**Tiempo estimado:** 3-4 días

**Resultado:** El juego "corre solo" en el servidor.

---

### 1.4 UI de Perfil del Piloto (Prioridad: MEDIA) 🟢

**Por qué cuarto:** Permite a los jugadores ver su progreso.

**Tareas:**
- [ ] Vista `character/profile.blade.php`
- [ ] Mostrar info básica (nombre, edad, créditos)
- [ ] Mostrar energía con barra visual
- [ ] Mostrar acción actual con progreso
- [ ] Listar habilidades con niveles y XP
- [ ] Agregar link en menú superior

**Tiempo estimado:** 3 días

**Resultado:** Jugadores ven su estado en tiempo real.

---

## 🎯 Fase 2: First Gameplay Loop (2-3 semanas)

**Objetivo:** Crear el primer ciclo completo de juego jugable.

### 2.1 Acción: Minería Básica

**Tareas:**
- [ ] Crear recurso `Resource` (tabla de inventario)
- [ ] Implementar `MiningActionHandler`
- [ ] Crear asteroid fields en sistema solar
- [ ] Vista de selección de asteroid field
- [ ] Requerir habilidad Mining L1
- [ ] Otorgar mineral al completar
- [ ] Otorgar XP de Mining

**Resultado:** Jugadores pueden minar y ver recursos en inventario.

---

### 2.2 Sistema de Inventario

**Tareas:**
- [ ] Migration para `character_inventory`
- [ ] Modelo `Inventory`
- [ ] Vista de inventario
- [ ] Mostrar recursos minados
- [ ] Sistema de capacidad (limitada por nave)

**Resultado:** Jugadores almacenan lo que consiguen.

---

### 2.3 Laboratorios e Inyección de Habilidades

**Tareas:**
- [ ] Migration para `laboratory_skills`
- [ ] Seeder para poblar laboratorios con habilidades
- [ ] Vista de laboratorio
- [ ] Lógica de inyección con validación de dependencias
- [ ] Cobro de créditos
- [ ] Vista de "catálogo" de habilidades disponibles

**Resultado:** Jugadores pueden aprender nuevas habilidades.

---

### 2.4 Primer Mercado Funcional

**Tareas:**
- [ ] Crear `Station Market` (vendedor NPC básico)
- [ ] Vender minerales por créditos
- [ ] Comprar items básicos (reparaciones, combustible)
- [ ] Vista de mercado simple

**Resultado:** Primer ciclo económico funcional.

---

## 🎯 Fase 3: Expansion (4-6 semanas)

### 3.1 Sistema de Naves Avanzado

- [ ] Tabla de módulos de nave
- [ ] Sistema de daño/integridad
- [ ] Múltiples naves por jugador
- [ ] Cambio entre naves en hangar
- [ ] Requerimientos de habilidades para pilotar

---

### 3.2 Viajes Espaciales

- [ ] Acción de viaje entre sistemas
- [ ] Consumo de combustible
- [ ] Cálculo de duración según distancia
- [ ] Random encounters (opcional)

---

### 3.3 Más Acciones Industriales

- [ ] Refinado de minerales
- [ ] Fabricación básica
- [ ] Trading entre estaciones

---

### 3.4 Sistema Social

- [ ] Chat global
- [ ] Mensajes privados
- [ ] Corporaciones (guilds)

---

## 📊 Métricas de Éxito (MVP)

**El juego es "jugable" cuando:**

1. ✅ Los jugadores pueden registrarse y elegir rol
2. ✅ Tienen habilidades según su rol
3. ✅ Pueden ejecutar al menos 2 acciones (descanso, minería)
4. ✅ Las acciones progresan automáticamente
5. ✅ Reciben XP y suben de nivel
6. ✅ Pueden aprender nuevas habilidades
7. ✅ Tienen una economía básica funcionando
8. ✅ El juego es estable y no crashea

---

## 🚀 Recomendación Inmediata

**Empezar con Fase 1.1 (Sistema de Habilidades)**

```bash
# Crear las migrations
php artisan make:migration create_skills_table
php artisan make:migration create_skill_dependencies_table
php artisan make:migration create_character_skills_table

# Crear los modelos
php artisan make:model Skill
php artisan make:model SkillDependency
php artisan make:model CharacterSkill

# Crear service
php artisan make:service SkillService

# Crear seeders
php artisan make:seeder SkillSeeder
php artisan make:seeder SkillDependencySeeder
```

**Orden de implementación sugerido:**
1. Migrations → 2. Models → 3. Seeders (data) → 4. Service (lógica) → 5. Controller → 6. Views

---

## 💡 Consejos

1. **Testea cada feature antes de continuar**
   - No acumules bugs
   - Usa Laravel Tinker para probar lógica

2. **Commits frecuentes y descriptivos**
   - Un feature = un commit
   - Facilita rollback si algo falla

3. **Documenta mientras programas**
   - Actualiza `/docs` cuando cambies algo importante
   - Escribe comentarios en código complejo

4. **Prioriza lo visible**
   - Aunque la lógica sea simple, la UI debe verse bien
   - Motiva a seguir desarrollando

5. **No optimices prematuramente**
   - Primero que funcione
   - Luego optimiza si es necesario

---

## ⏱️ Timeline Realista

- **Fase 1 (Core):** 3-4 semanas
- **Fase 2 (First Loop):** 2-3 semanas
- **MVP Completo:** 6-8 semanas de desarrollo
- **Beta Privada:** +2 semanas de testing
- **Launch Público:** +2 semanas de polish

**Total hasta launch:** ~3 meses (asumiendo trabajo part-time)

---

## 🎮 Visión a Largo Plazo

Después del MVP, el juego puede crecer hacia:

- Combate PvE (contra NPCs)
- Combate PvP (entre jugadores)
- Construcción de estaciones (player-owned)
- Exploración de wormholes
- Eventos globales
- Economía player-driven
- Misiones y quest lines
- Achievements y rankings

**Pero primero:** Core sólido, estable, y divertido.
