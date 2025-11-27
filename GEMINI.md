# Project Standards & Preferences

## Tech Stack
- **Framework**: Laravel
- **CSS Framework**: Tailwind CSS
- **Component Architecture**: Laravel Server-Side Components (Blade Components)

## Design Philosophy
- **Style**: Text-based MMO, "Popmundo" inspired.
- **Theme**: Sci-fi, Space Pilot Sandbox.
- **UI**: Clean, functional, text-heavy but visually appealing.

## Rules
1. **Tailwind CSS**: Use Tailwind for all styling.
2. **Laravel Components**: Prioritize server-side Blade components for UI elements.
3. **Server-Side Only**: No client-side frameworks (React/Vue/etc.). All logic and rendering must be server-side.
4. **Responsiveness**: The site MUST be fully responsive on all devices. This is non-negotiable.
5. **Language**: All content stored in the database (names, descriptions, etc.) MUST be in Spanish.
6. **Componentization**: Maintain a homogeneous UI by creating reusable Blade components for common UI elements (sections, menus, modals). Avoid code duplication in views.
