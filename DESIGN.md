---
name: Nuwesoft
description: Taller digital audaz, tecnológico y confiable.
colors:
    ink: "#0A0A0B"
    paper: "#FFFFFF"
    signal-orange: "#FF4400"
    signal-pink: "#FF2E63"
    electric-cyan: "#00F0FF"
    action-indigo: "#6366F1"
    success-emerald: "#10B981"
typography:
    display:
        fontFamily: "Space Grotesk, sans-serif"
        fontWeight: 900
        lineHeight: 0.95
        letterSpacing: "-0.03em"
    body:
        fontFamily: "Outfit, sans-serif"
        fontSize: "1rem"
        fontWeight: 400
        lineHeight: 1.5
    label:
        fontFamily: "Outfit, sans-serif"
        fontSize: "0.875rem"
        fontWeight: 700
        lineHeight: 1.25
rounded:
    sharp: "0px"
    control: "12px"
    panel: "24px"
spacing:
    xs: "4px"
    sm: "8px"
    md: "16px"
    lg: "24px"
    xl: "32px"
components:
    button-primary:
        backgroundColor: "{colors.ink}"
        textColor: "{colors.paper}"
        rounded: "{rounded.control}"
        padding: "12px 16px"
        height: "44px"
    input:
        backgroundColor: "{colors.paper}"
        textColor: "{colors.ink}"
        rounded: "{rounded.control}"
        padding: "10px 12px"
        height: "44px"
---

# Design System: Nuwesoft

## Overview

**Creative North Star: "El Taller Digital"**

Nuwesoft combina la energía visible de un estudio que construye con la precisión de una herramienta profesional. La web pública puede ser expresiva; el dashboard es más silencioso y operativo, pero ambos comparten contraste fuerte, tipografía segura y detalles de señalización.

Se rechazan las plantillas corporativas genéricas, la saturación decorativa y el microtexto. La jerarquía, el espacio y los estados accesibles mandan sobre cualquier efecto.

## Colors

La base es monocromática y los colores eléctricos se reservan para llamadas, identidad y estados inequívocos. Naranja y rosa expresan marca; índigo identifica acciones de producto; esmeralda confirma éxito. El color nunca funciona sin texto o icono asociado.

## Typography

**Display Font:** Space Grotesk, sans-serif  
**Body Font:** Outfit, sans-serif

Los titulares son compactos y contundentes. El texto operativo usa un mínimo de 14 px, interlineado cómodo y mayúsculas solo en etiquetas breves; nunca se comprime información funcional a 8–10 px.

## Elevation

La web pública admite sombras neobrutalistas duras como gesto de marca. El dashboard utiliza bordes, contraste tonal y sombras ambientales moderadas. Las superficies no deben competir entre sí por elevación.

## Components

Los controles interactivos tienen al menos 44×44 px, foco visible y estados hover, active, disabled y loading. Botones, tooltips, diálogos e inputs reutilizan los componentes compartidos; las tarjetas reordenan su contenido antes de comprimirlo. Los estados combinan color, icono y texto.

## Do's and Don'ts

### Do:

- **Do** mantener WCAG AA, foco visible y navegación completa por teclado.
- **Do** usar Space Grotesk para identidad y Outfit para lectura sostenida.
- **Do** reservar los acentos eléctricos para información con significado.
- **Do** respetar `prefers-reduced-motion` en toda animación no esencial.

### Don't:

- **Don't** producir una plantilla corporativa genérica.
- **Don't** saturar la interfaz con adornos, gradientes o sombras simultáneas.
- **Don't** usar microtexto operativo de 8–10 px.
- **Don't** reducir controles por debajo de 44×44 px.
