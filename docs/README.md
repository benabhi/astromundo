# Documentación del Proyecto VaxaV

Este directorio contiene la documentación técnica completa del juego MMO de texto VaxaV.

---

## Índice de Documentos

### Core Systems (Sistemas Principales)

1. **[Sistema de Ticks](./01-tick-system.md)**
   - Explicación del sistema de actualización del juego
   - Opciones de frecuencia de tick
   - Arquitectura técnica
   - Procesamiento de acciones

2. **[Sistema de Habilidades](./02-skill-system.md)**
   - Progresión de personajes
   - Niveles y multiplicadores
   - Dependencias entre habilidades
   - Inyección en laboratorios
   - Roles iniciales

3. **[Arquitectura General](./03-architecture.md)**
   - Stack tecnológico
   - Estructura de directorios
   - Patrones de diseño recomendados
   - Optimizaciones y seguridad

### Game Features (Características del Juego)

4. **Economía** (Pendiente)
   - Sistema de créditos
   - Mercados y comercio
   - Recursos y materiales
   - Fabricación

5. **Naves y Equipamiento** (Pendiente)
   - Tipos de naves
   - Módulos de nave
   - Requerimientos de habilidades
   - Sistema de daño/reparación

6. **UI/UX Design** (Pendiente)
   - Wireframes
   - Flujos de navegación
   - Componentes reutilizables
   - Estilo visual

### Development (Desarrollo)

99. **[Roadmap](./99-roadmap.md)** (Pendiente)
    - Fases de desarrollo
    - Prioridades
    - Milestones

---

## Cómo usar esta documentación

### Para Desarrolladores

1. Lee primero `03-architecture.md` para entender la estructura
2. Consulta sistemas específicos según la feature que implementes
3. Sigue los patrones establecidos

### Para Diseñadores

1. Revisa `06-ui-ux-design.md` para componentes visuales
2. Consulta sistemas de juego para entender mecánicas
3. Mantén consistencia con el estilo sci-fi

### Para Product Owners

1. Lee `99-roadmap.md` para prioridades
2. Consulta documentos de features específicas
3. Propón cambios via issues

---

## Convenciones

### Estructura de Documentos

```markdown
# Título del Sistema

## Concepto General
[Breve explicación]

## Base de Datos
[Esquemas y tablas]

## Lógica de Negocio
[Código y ejemplos]

## UI/UX
[Mockups y flujos]

## Próximos Pasos
[Lista de tareas]
```

### Formato de Código

- **SQL:** Usa backticks para tablas y columnas
- **PHP:** PSR-12 compliant
- **Blade:** Indentación de 4 espacios

---

## Contribuir

1. Crea un branch para la feature
2. Actualiza documentación en `docs/`
3. Implementa código siguiendo arquitectura
4. Solicita review

---

## Estado del Proyecto

**Última actualización:** 27 de noviembre, 2025

**Fase actual:** Diseño de sistemas base

**Próximo milestone:** MVP con sistema de ticks y habilidades

---

## Contacto

- **Desarrollador Principal:** [Tu nombre]
- **Repositorio:** https://github.com/benabhi/astromundo
