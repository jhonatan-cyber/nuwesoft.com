# Plan de mejoras y seguimiento del sistema

Última actualización: 2026-08-26

## Objetivo

Fortalecer la seguridad, confiabilidad operativa, rendimiento y experiencia de uso de Nuwesoft sin interrumpir el sistema ni perder información almacenada en PostgreSQL o Cloudinary.

## Estado comprobado

- Backend: regresión comprobada sin fallos ni pruebas riesgosas. Los casos de adjuntos se aislaron de Cloudinary con colas simuladas.
- Frontend: 63 pruebas aprobadas.
- Build de producción: correcto.
- JavaScript: sin vulnerabilidades conocidas.
- PHP: sin alertas de seguridad conocidas (`composer audit`).
- Auditoría visual: 10/20; principales problemas en tamaños táctiles, microtexto y densidad.

## Tablero de seguimiento

Estados: `Pendiente`, `En curso`, `Bloqueado`, `Completado`.

| ID      | Prioridad | Mejora                                               | Estado     | Criterio de aceptación                                                                                                   |
| ------- | --------- | ---------------------------------------------------- | ---------- | ------------------------------------------------------------------------------------------------------------------------ |
| SEG-01  | P0        | Cerrar el registro administrativo directo            | Completado | Un POST manual a `/register` no crea usuarios cuando ya existe un administrador.                                         |
| SEG-02  | P0        | Agregar roles y autorización administrativa          | Completado | El dashboard requiere permiso administrativo, no solo autenticación.                                                     |
| SEG-03  | P0        | Sanitizar Markdown y HTML del blog                   | Completado | Scripts, eventos HTML y protocolos inseguros no se guardan ni ejecutan.                                                  |
| SEG-04  | P0        | Aislar el capturador contra SSRF                     | Completado | Chromium bloquea IP privadas, cambios de origen y protocolos no permitidos en cada request.                              |
| DEP-01  | P0        | Actualizar Laravel, Symfony, Guzzle y CommonMark     | Completado | `composer audit` no reporta vulnerabilidades altas o medias aplicables.                                                  |
| OPS-01  | P1        | Persistir y comprobar respaldos de PostgreSQL        | Completado | El backup queda fuera del contenedor, se valida y puede restaurarse.                                                     |
| OPS-02  | P1        | Condicionar Deploy al éxito de CI                    | Completado | Producción solo se despliega después de pruebas, análisis y build exitosos.                                              |
| OPS-03  | P1        | Mejorar rollback de despliegues y migraciones        | En curso   | Un fallo restaura versión, contenedores y base de datos de forma verificable.                                            |
| MED-01  | P1        | Permitir reintentos reales en trabajos de Cloudinary | Completado | Los fallos se reintentan y terminan en `failed_jobs` cuando corresponde.                                                 |
| MED-02  | P1        | Hacer consistente la eliminación de imágenes         | Completado | Un fallo parcial no deja registros apuntando a imágenes inexistentes.                                                    |
| MED-03  | P1        | Mostrar estado de capturas y subidas                 | Completado | El dashboard informa pendiente, procesando, completado o fallido.                                                        |
| API-01  | P1        | Reducir información pública de `/health`             | Completado | El endpoint público solo devuelve estado; métricas y errores requieren autorización.                                     |
| QA-01   | P1        | Ejecutar Vitest y ESLint en CI                       | Completado | CI ejecuta pruebas frontend y lint además del build.                                                                     |
| QA-02   | P1        | Añadir pruebas E2E de flujos críticos                | En curso   | Playwright cubre login, CRUD de proyectos, estado y eliminación.                                                         |
| UX-01   | P2        | Aumentar objetivos táctiles a 44 px                  | Completado | Todas las acciones principales cumplen un mínimo de 44×44 px.                                                            |
| UX-02   | P2        | Eliminar microtexto de 8–10 px                       | Completado | El texto operativo es legible y cumple contraste WCAG AA.                                                                |
| UX-03   | P2        | Ajustar densidad y columnas del dashboard            | En curso   | Las tarjetas no se comprimen ni pierden acciones en ningún ancho.                                                        |
| UX-04   | P2        | Unificar colores con tokens semánticos               | Completado | Estados, fondos y textos son consistentes en tema claro y oscuro.                                                        |
| PERF-01 | P2        | Cargar Markdown/highlight bajo demanda               | Completado | El editor no aumenta el bundle de páginas que no lo utilizan.                                                            |
| PERF-02 | P2        | Dividir componentes mayores de 400 líneas            | Completado | Ningún archivo Vue/JS/TS supera 400 líneas; lógica, estilos y secciones visuales quedaron separados por responsabilidad. |
| OPS-04  | P2        | Fijar versiones de imágenes Docker                   | Completado | Producción no depende de etiquetas mutables sin versión.                                                                 |
| DOC-01  | P2        | Documentar desarrollo, túnel, deploy y recuperación  | Completado | README permite levantar, diagnosticar y restaurar el sistema.                                                            |

## Fases

1. **Seguridad:** SEG-01 a SEG-04 y DEP-01.
2. **Datos y despliegue:** OPS-01 a OPS-03, MED-01, MED-02 y API-01.
3. **Calidad automatizada:** QA-01, QA-02 y MED-03.
4. **UX y rendimiento:** UX-01 a UX-04, PERF-01 y PERF-02.
5. **Operación:** OPS-04 y DOC-01; ensayar backup y rollback en staging.

## Rutina por ciclo

1. Seleccionar como máximo tres tareas.
2. Cambiarlas a `En curso` antes de modificar código.
3. Añadir o actualizar pruebas junto con cada corrección.
4. Ejecutar backend tests, Vitest, ESLint, PHPStan, build y auditorías.
5. Marcar `Completado` únicamente al cumplir el criterio de aceptación.
6. Registrar debajo cualquier decisión, bloqueo o resultado relevante.

## Registro de avances

| Fecha      | ID             | Cambio                                                                                                                                                                                | Resultado                                                                                                                                                                                                                    | Responsable |
| ---------- | -------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------- |
| 2026-08-25 | MANT-01        | Retiro del plan antiguo ya ejecutado y del modal de galería desactivado                                                                                                               | Completado                                                                                                                                                                                                                   | Codex       |
| 2026-08-25 | SEG-01/02      | Registro único, rol administrativo y middleware de autorización                                                                                                                       | 15 pruebas y 61 assertions aprobadas                                                                                                                                                                                         | Codex       |
| 2026-08-25 | SEG-03         | Sanitización DOMPurify y serialización segura de JSON-LD                                                                                                                              | 16 pruebas aprobadas                                                                                                                                                                                                         | Codex       |
| 2026-08-25 | SEG-04         | Filtro de red por request y bloqueo de cambio de origen                                                                                                                               | 17 pruebas aprobadas; localhost bloqueado                                                                                                                                                                                    | Codex       |
| 2026-08-25 | DEP-01         | Actualización compatible de Laravel, Symfony, Guzzle y CommonMark                                                                                                                     | `composer audit` sin vulnerabilidades y PHPStan sin errores                                                                                                                                                                  | Codex       |
| 2026-08-25 | FASE-01        | Regresión completa de seguridad                                                                                                                                                       | 261 pruebas backend, 63 frontend, Pint, PHPStan y build aprobados                                                                                                                                                            | Codex       |
| 2026-08-25 | OPS-01/02      | Volumen persistente y backup validado antes de deploy; deploy posterior a CI                                                                                                          | Compose válido y despliegue se cancela ante CI/backup fallido                                                                                                                                                                | Codex       |
| 2026-08-25 | QA-01          | ESLint y Vitest añadidos al flujo CI                                                                                                                                                  | 63 pruebas aprobadas; lint sin errores y con deuda de advertencias registrada                                                                                                                                                | Codex       |
| 2026-08-25 | MED-01         | Excepciones propagadas, 3 intentos con backoff y limpieza final del temporal                                                                                                          | Pruebas de reintento y fallo final aprobadas                                                                                                                                                                                 | Codex       |
| 2026-08-25 | MED-02         | Eliminación local confirmada por cada borrado remoto exitoso                                                                                                                          | Prueba de fallo parcial aprobada sin referencias obsoletas                                                                                                                                                                   | Codex       |
| 2026-08-25 | API-01         | Health público mínimo y diagnóstico completo exclusivo para administradores                                                                                                           | Pruebas de invitado, usuario y administrador aprobadas                                                                                                                                                                       | Codex       |
| 2026-08-25 | REG-02         | Regresión de medios, proyectos y health; aislamiento de adjuntos en tests                                                                                                             | 16 pruebas específicas y 10 de contacto aprobadas; Pint y PHPStan sin errores                                                                                                                                                | Codex       |
| 2026-08-25 | MED-03         | Estado y contador de cargas por proyecto, error visible y actualización segura desde la cola                                                                                          | 12 pruebas de proyectos/medios y build aprobados; migración aplicada                                                                                                                                                         | Codex       |
| 2026-08-25 | OPS-03         | Comando de restauración validado y rollback automático integrado al deploy                                                                                                            | Implementado; pendiente simulacro destructivo en staging                                                                                                                                                                     | Codex       |
| 2026-08-25 | QA-02          | Flujo Playwright de login, alta, edición, estado y eliminación añadido a CI                                                                                                           | Test descubierto correctamente; pendiente primera ejecución en CI aislado                                                                                                                                                    | Codex       |
| 2026-08-25 | OPS-04         | Imágenes de Composer, Bun, PHP/Nginx, PostgreSQL y Redis fijadas por digest                                                                                                           | Compose y validación estática del Dockerfile aprobados                                                                                                                                                                       | Codex       |
| 2026-08-25 | DOC-01         | Guía de desarrollo, túnel, pruebas, Cloudinary, backup, deploy y recuperación                                                                                                         | README actualizado con procedimientos seguros                                                                                                                                                                                | Codex       |
| 2026-08-26 | UX-01/02       | Controles compartidos y nativos a 44 px; eliminación sistemática de microtexto del dashboard                                                                                          | Escaneo limpio, 63 pruebas frontend y build aprobados                                                                                                                                                                        | Codex       |
| 2026-08-26 | PERF-01        | Markdown, highlight y DOMPurify conservados en un chunk exclusivo de las rutas que los usan                                                                                           | Build confirma aislamiento del chunk; páginas generales no lo descargan                                                                                                                                                      | Codex       |
| 2026-08-26 | PERF-02        | Listados, Servicios, Portafolio y detalle de proyecto divididos en composables, estilos y componentes especializados                                                                  | Escaneo sin archivos mayores de 400 líneas, 63 pruebas aprobadas y build de producción correcto                                                                                                                              | Codex       |
| 2026-08-26 | UX-03/04       | Proyectos limitados a tres columnas; estados de dashboard y confirmaciones migrados a tokens success, warning, danger e info                                                          | 63 pruebas aprobadas, build correcto y lint sin errores; UX-03 queda pendiente de verificación visual responsive                                                                                                             | Codex       |
| 2026-08-26 | QA-02          | E2E ejecutado contra PostgreSQL local `testing`; espera de persistencia y timeouts ajustados                                                                                          | El servidor PHP monohilo sobre el volumen Windows se saturó sirviendo assets; el flujo queda en curso hasta ejecutarse en CI Linux                                                                                           | Codex       |
| 2026-08-26 | QA-02/UX-03    | CI E2E aislado de Redis y prueba responsive añadida para 390, 768 y 1440 px                                                                                                           | Valida máximo de 1/2/3 columnas, acciones visibles y ausencia de desbordamiento horizontal; pendiente resultado del runner Linux                                                                                             | Codex       |
| 2026-08-26 | REG-03         | Regresión completa forzando SQLite en memoria para aislarla del túnel remoto; corregida la validación del token antispam en la respuesta Inertia                                      | 258 pruebas y 847 assertions aprobadas, sin pruebas riesgosas; PHPStan y Pint correctos                                                                                                                                      | Codex       |
| 2026-08-26 | OPS-04/PERF-02 | Servidor local configurado con cuatro workers PHP y túnel SSH remoto restablecido                                                                                                     | `/login` y `/health` responden 200; las peticiones ya no quedan bloqueadas detrás del healthcheck                                                                                                                            | Codex       |
| 2026-08-26 | QA-02/UX-03    | Ensayo E2E repetido en contenedor Linux y PostgreSQL temporal `e2e_codex`; corregida la creación del administrador verificado con `forceCreate` y ampliada la espera de autenticación | El montaje Docker/Windows devuelve autenticación web inválida aunque `Auth::attempt` dentro del mismo contenedor es correcto; entorno temporal eliminado. Pendiente runner Linux, bloqueado por credenciales GitHub vencidas | Codex       |
| 2026-08-26 | DESIGN-01      | Contexto de producto y sistema visual documentados                                                                                                                                    | Dirección audaz, tecnológica, confiable y WCAG AA registrada en PRODUCT.md/DESIGN.md                                                                                                                                         | Codex       |

## Protección durante limpiezas

No eliminar sin revisión específica:

- `.env`, `.env.tunnel` y credenciales.
- `public/build` mientras se sirvan assets de producción.
- `storage/app/private/temp` si existen trabajos pendientes.
- Backups y volúmenes de PostgreSQL/Redis.
- Logs recientes necesarios para diagnóstico.
- Cambios locales sin confirmar en Git.
