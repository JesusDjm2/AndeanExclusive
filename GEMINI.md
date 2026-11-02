# Estándares de Ingeniería y Diseño Senior — Andean Exclusive

Este documento define las directrices obligatorias de diseño, arquitectura, programación y experiencia de usuario aplicadas en todo el proyecto **Andean Exclusive**.

---

## 1. Diseño y Estética Visual (Senior UI)
- **Identidad de Lujo y Distinción**: Andean Exclusive es una agencia de viajes boutique de alta gama. Cada pantalla debe transmitir exclusividad, serenidad y elegancia.
- **Paleta Cromática Consistente**:
  - Fondos y textos principales: Ink/Navy profundo (`#182229`, `#0f172a`), contrastados sobre fondos blancos puros (`#ffffff`) o grises suaves de lujo (`#f8fafc`).
  - Acentos de Marca: Tonos esmeralda y teal andino (`#06b6d4`, `#0891b2`, `#0f766e`), acompañados de acentos dorados/cálidos sutiles cuando corresponda.
- **Tipografía y Escala Visual**:
  - Jerarquía clara de títulos (`h1` a `h6`) utilizando `clamp()` para escalabilidad tipográfica fluida entre pantallas móviles y de escritorio.
  - Lecturabilidad impecable: `line-height` cómodo (1.5 a 1.7 en párrafos), contraste de color conforme a WCAG AA (mínimo 4.5:1 para texto normal, 3:1 para titulares grandes).
- **Espaciado y Ritmo Visual**:
  - Respiro generoso: evitar saturación de elementos. Uso de múltiplos de 8px (o 4px para microespacios) para `gap`, `margin` y `padding`.
  - Tarjetas y Contenedores: sombras de elevación multicapa suaves y naturales (`box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08)`), bordes redondeados armónicos (`border-radius: 12px` a `20px`) y bordes translúcidos pulidos.

---

## 2. Experiencia de Usuario (Senior UX) y Navegabilidad
- **Fricción Cero en Flujos Clave**: Formularios de consulta rápida, cotizaciones y reservas deben ser directos, sin pasos innecesarios ni campos redundantes.
- **Feedback Inmediato de Estado**:
  - Cada elemento interactivo debe contar con estados claros y accesibles: *default*, *hover*, *focus-visible* (anillo de foco visible para teclado), *active*, *loading* (spinners o deshabilitado temporal durante envíos) y *disabled*.
  - Mensajes de confirmación y error elegantes, comprensibles y no intrusivos.
- **Navegabilidad Fluida**:
  - Cabecera fija o flotante con cálculo exacto de altura (`var(--header-h)`).
  - Toda navegación por anclas (`#id`) debe incluir `scroll-margin-top: calc(var(--header-h) + 1.5rem)` o compensación adecuada para que ningún título o contenido quede oculto bajo la cabecera.
  - Gestión del scroll: restaurar scroll adecuadamente tras interacciones modales o redirecciones de formulario sin saltos bruscos.

---

## 3. Responsive Design y Mobile-First
- **Construcción Mobile-First**: Los estilos base se diseñan para pantallas móviles (< 640px) y se enriquecen progresivamente hacia tablets y desktops mediante `@media (min-width: ...)`.
- **Viewport Dinámico**:
  - Utilizar unidades modernas `100dvh` (con fallback de `100vh`) para contenedores de pantalla completa (Hero, modales, overlays), previniendo saltos causados por las barras de navegación en iOS y Android.
- **Cero Desbordamiento Horizontal**:
  - Supervisión estricta de `overflow-x`. Contenedores con `max-width: 100%` y uso de `box-sizing: border-box`.
- **Zonas Táctiles Confortables**:
  - Todo botón, link o selector interactivo en versión móvil debe contar con un área táctil mínima de 44×44px (o 48×48px), con márgenes suficientes para evitar toques accidentales.

---

## 4. Animaciones y Micro-interacciones (GSAP Senior)
- **Rendimiento Acelerado por GPU**:
  - Animar exclusivamente propiedades de transformación (`x`, `y`, `scale`, `rotation`) y `opacity`.
  - Evitar animar propiedades que provoquen *layout thrashing* o *reflow* (`top`, `left`, `width`, `height`, `margin`).
- **Líneas de Tiempo Coherentes (`gsap.timeline`)**:
  - Duraciones naturales (0.4s a 0.8s), curvas de aceleración orgánicas (`power2.out`, `power3.out`, `expo.out`).
  - Animaciones de revelación escalonadas (*stagger* de 0.05s a 0.12s) para listas de tarjetas, títulos e imágenes.
- **ScrollTrigger Optimizado**:
  - Pines y triggers configurados con marcadores bien calculados. Refrescar (`ScrollTrigger.refresh()`) tras cargas asíncronas de imágenes o cambios dinámicos en el DOM.
- **Accesibilidad Motriz (`prefers-reduced-motion`)**:
  - Respetar las preferencias del sistema operativo reduciendo o desactivando movimientos para usuarios sensibles al movimiento.

---

## 5. Lógica de Negocio y Programación Senior (PHP & JS)
- **Código Limpio y Principios SOLID**:
  - Funciones y métodos con responsabilidad única. Nombres descriptivos en español para el dominio del negocio (reservas, cotizaciones, consultas, servicios).
  - Evitar duplicación (DRY) y mantener las soluciones lo más directas posible (KISS).
- **Programación Defensiva**:
  - Validación estricta y tipado firme en backend y frontend.
  - Manejo robusto de errores con bloques `try/catch`, logging detallado en `storage/logs/laravel.log` y mensajes amigables al usuario final.
  - Operaciones críticas en base de datos protegidas con transacciones (`DB::transaction`).

---

## 6. Arquitectura y Buenas Prácticas en Laravel 11
- **Controladores Delgados**: La lógica de negocio pesada, integración de correo y cálculos residen en Servicios, Actions o Traits.
- **Validación con Form Requests**: Formularios complejos validan mediante clases `FormRequest` con reglas precisas y mensajes localizados en español.
- **Eloquent de Alto Rendimiento**:
  - Prevención activa de consultas N+1 utilizando Eager Loading (`with(['relacion1', 'relacion2'])`).
  - Selección optimizada de columnas necesarias (`select(['id', 'nombre', ...])`) en consultas de listados extensos.
- **Blade y Componentes Modulares**:
  - Vistas estructuradas en componentes limpios y reutilizables en `resources/views/components/` o `partials/`.
- **Seguridad**:
  - Protección CSRF activa en todos los formularios POST.
  - Autorización mediante Policies o Spatie Roles/Permissions (`can('ver-consultas')`, etc.).
  - Encabezados de seguridad HTTP (CSP, HSTS, X-Content-Type-Options) y protección contra XSS y SQL Injection.
- **Despliegues y Producción**:
  - Migraciones de base de datos idempotentes y seguras (`Schema::hasTable`, `Schema::hasColumn`).
  - Manejo cuidadoso de la compatibilidad con el servidor de producción (PHP 8.2+, handler cPanel preservado en `.htaccess`).
