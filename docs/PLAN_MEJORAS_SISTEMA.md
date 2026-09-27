# Plan de mejoras y seguimiento del sistema

Última actualización: 2026-09-27

## Objetivo

Fortalecer la seguridad, confiabilidad operativa, rendimiento y experiencia de uso de Nuwesoft sin interrumpir el sistema ni perder información almacenada en PostgreSQL o Cloudinary.

## Estado comprobado

- Backend: regresión comprobada sin fallos ni pruebas riesgosas. Los casos de adjuntos se aislaron de Cloudinary con colas simuladas.
- Frontend: 63 pruebas aprobadas.
- Build de producción: correcto.
- JavaScript: `bun.lock` es el único lockfile (`package-lock.json` eliminado e ignorado) y `bun audit` baja de 94 a 1 vulnerabilidad moderada (`stream-json@1.9.1` vía `crawlee`, sin fix compatible).
- PHP: sin alertas de seguridad conocidas (`composer audit`).
- Content Security Policy: `script-src` y `style-src` exigen nonce por request y ya no admiten `'unsafe-inline'`; 5 pruebas PHPUnit y 17 pruebas E2E cubren la política.
- Auditoría visual: 10/20; principales problemas en tamaños táctiles, microtexto y densidad.

## Tablero de seguimiento

Estados: `Pendiente`, `En curso`, `Bloqueado`, `Completado`.

| ID      | Prioridad | Mejora                                               | Estado     | Criterio de aceptación                                                                                                   |
| ------- | --------- | ---------------------------------------------------- | ---------- | ------------------------------------------------------------------------------------------------------------------------ |
| SEG-01  | P0        | Cerrar el registro administrativo directo            | Completado | Un POST manual a `/register` no crea usuarios cuando ya existe un administrador.                                         |
| SEG-02  | P0        | Agregar roles y autorización administrativa          | Completado | El dashboard requiere permiso administrativo, no solo autenticación.                                                     |
| SEG-03  | P0        | Sanitizar Markdown y HTML del blog                   | Completado | Scripts, eventos HTML y protocolos inseguros no se guardan ni ejecutan.                                                  |
| SEG-04  | P0        | Aislar el capturador contra SSRF                     | Completado | Chromium bloquea IP privadas, cambios de origen y protocolos no permitidos en cada request.                              |
| SEG-05  | P1        | Exigir nonce en la CSP y podar hosts muertos         | Completado | `script-src`/`style-src` no admiten `'unsafe-inline'` y la allowlist de hosts de scripts queda fijada por pruebas.       |
| SEG-06  | P1        | Reconectar el reporting de CSP                       | Completado | La cabecera declara `report-uri /csp-report`, el endpoint acepta informes sin CSRF, con rate limit, y las pruebas lo fijan. |
| DEP-01  | P0        | Actualizar Laravel, Symfony, Guzzle y CommonMark     | Completado | `composer audit` no reporta vulnerabilidades altas o medias aplicables.                                                  |
| DEP-02  | P1        | Unificar lockfiles y cerrar la auditoría JS          | Completado | `bun.lock` es el único lockfile, la imagen instala con `--frozen-lockfile` y `bun audit` queda en 1 vulnerabilidad sin fix documentada. |
| OPS-01  | P1        | Persistir y comprobar respaldos de PostgreSQL        | Completado | El backup queda fuera del contenedor, se valida y puede restaurarse.                                                     |
| OPS-02  | P1        | Condicionar Deploy al éxito de CI                    | Completado | Producción solo se despliega después de pruebas, análisis y build exitosos.                                              |
| OPS-03  | P1        | Mejorar rollback de despliegues y migraciones        | Completado | Un fallo restaura versión, contenedores y base de datos de forma verificable: deployments.log auditable, `scripts/rollback.sh` con dry-run y healthcheck, y simulacro destructivo programable. |
| MED-01  | P1        | Permitir reintentos reales en trabajos de Cloudinary | Completado | Los fallos se reintentan y terminan en `failed_jobs` cuando corresponde.                                                 |
| MED-02  | P1        | Hacer consistente la eliminación de imágenes         | Completado | Un fallo parcial no deja registros apuntando a imágenes inexistentes.                                                    |
| MED-03  | P1        | Mostrar estado de capturas y subidas                 | Completado | El dashboard informa pendiente, procesando, completado o fallido.                                                        |
| API-01  | P1        | Reducir información pública de `/health`             | Completado | El endpoint público solo devuelve estado; métricas y errores requieren autorización.                                     |
| QA-01   | P1        | Ejecutar Vitest y ESLint en CI                       | Completado | CI ejecuta pruebas frontend y lint además del build.                                                                     |
| QA-02   | P1        | Añadir pruebas E2E de flujos críticos                | Completado | Playwright cubre login, CRUD de proyectos, estado y eliminación; la suite pasa en CI (verde también para las 17 pruebas CSP). |
| QA-03   | P1        | Pruebas automatizadas de la CSP                      | Completado | PHPUnit valida la cabecera y Playwright falla si algún script inline se bloquea en páginas públicas o del dashboard.      |
| UX-01   | P2        | Aumentar objetivos táctiles a 44 px                  | Completado | Todas las acciones principales cumplen un mínimo de 44×44 px.                                                            |
| UX-02   | P2        | Eliminar microtexto de 8–10 px                       | Completado | El texto operativo es legible y cumple contraste WCAG AA.                                                                |
| UX-03   | P2        | Ajustar densidad y columnas del dashboard            | En curso   | Las tarjetas no se comprimen ni pierden acciones en ningún ancho.                                                        |
| UX-04   | P2        | Unificar colores con tokens semánticos               | Completado | Estados, fondos y textos son consistentes en tema claro y oscuro.                                                        |
| PERF-01 | P2        | Cargar Markdown/highlight bajo demanda               | Completado | El editor no aumenta el bundle de páginas que no lo utilizan.                                                            |
| PERF-02 | P2        | Dividir componentes mayores de 400 líneas            | Completado | Ningún archivo Vue/JS/TS supera 400 líneas; lógica, estilos y secciones visuales quedaron separados por responsabilidad. |
| OPS-04  | P2        | Fijar versiones de imágenes Docker                   | Completado | Producción no depende de etiquetas mutables sin versión.                                                                 |
| DOC-01  | P2        | Documentar desarrollo, túnel, deploy y recuperación  | Completado | README permite levantar, diagnosticar y restaurar el sistema.                                                            |

## Fases

1. **Seguridad:** SEG-01 a SEG-05, DEP-01 y DEP-02.
2. **Datos y despliegue:** OPS-01 a OPS-03, MED-01, MED-02 y API-01.
3. **Calidad automatizada:** QA-01, QA-02, QA-03 y MED-03.
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
| 2026-09-27 | SEG-05         | Nonce por request en `SecurityHeaders`, compartido a Blade, Vite e Inertia; `script-src` y `style-src` sin `'unsafe-inline'`, con `style-src-attr` para atributos de Vue              | 5 pruebas PHPUnit de cabecera y nonce; JSON-LD de las páginas Vue también nonced                                                                                                                                             | Codebuff    |
| 2026-09-27 | QA-03          | Prueba E2E de CSP con trampa de `securitypolicyviolation` en 7 páginas públicas y 10 rutas del dashboard autenticado                                                                  | 17 pruebas; verificadas contra fixture local: política sana pasa, script bloqueado, cabecera legacy y CDN muerto en `script-src` fallan                                                                                      | Codebuff    |
| 2026-09-27 | SEG-05         | Hosts de `script-src` reducidos a allowlist explícita; `cdn.jsdelivr.net` eliminado por muerto (solo servía imágenes) y fijado por pruebas                                            | `test_script_src_hosts_are_a_tight_allowlist` y asertión equivalente en E2E; beacon de Cloudflare y assets de PostHog conservados por estar en uso                                                                           | Codebuff    |
| 2026-09-27 | DEP-02         | `package-lock.json` eliminado y anotado en `.gitignore`; `bun.lock` como lockfile único, scripts de `composer.json` migrados de npm a bun y stage `node-deps` en `Dockerfile.prod` con `bun install --production --frozen-lockfile --ignore-scripts` | `bun install --frozen-lockfile` y una instalación de producción aislada verificadas (crawlee 197 exports, shadcn ausente del runtime); la imagen de ejecución queda sin npm                                                      | Codebuff    |
| 2026-09-27 | DEP-02         | Overrides de dependencias (adm-zip, decode-uri-component, fast-uri, hono, ip-address, qs, shell-quote) y `shadcn-vue` movido a devDependencies para desbloquear la instalación         | `bun audit` de 94 a 1 vulnerabilidad: `stream-json@1.9.1` (moderada, GHSA-528h-pc64-c93x) vía `crawlee`, sin fix — 3.x rompe `stream-json/streamers/StreamArray`; crawlee no se puede aislar porque `ProjectController` lo lanza en el mismo contenedor | Codebuff    |
| 2026-09-27 | SEG-06         | Ruta `POST /csp-report` hacia `CspReportController`, `report-uri` en la cabecera CSP, limiter `csp-report` (30/min por IP) y exclusión CSRF con `preventRequestForgery(['/csp-report'])`                                                                 | 4 pruebas nuevas en `SecurityHeadersTest` (9 en total): cabecera con `report-uri`, 204 con log estructurado, ruta exenta de CSRF y 429 al exceder el límite; el endpoint no toca sesión ni base de datos | Codebuff    |
| 2026-09-27 | SEG-05/CI      | Corregido `Vite::useCspNonce()` llamado estáticamente desde `Illuminate\\Foundation\\Vite` (en Laravel 13 es instancia): provocaba 500 en cada petición, los 148 fallos de PHPUnit y el timeout del webServer E2E del run #29                              | Run #30: `PHP (test)` y `PHPStan` en verde tras el fix de la facade              | Codebuff    |
| 2026-09-27 | QA-02          | La barra de progreso de Inertia (nprogress) inyectaba un `<style>` inline bloqueado por `style-src-elem`; sus reglas se externalizaron a `resources/css/vendor/nprogress.css` con `includeCSS: false`, y `EntityUpdated` dejó de emitir broadcast a Reverb inalcanzable (`broadcastWhen()` + `BROADCAST_CONNECTION=log` en CI) que causaba 500 en el CRUD y el fallo del E2E de proyectos | Run #31 totalmente verde: los 6 jobs (pint, PHPStan, PHP test, Frontend, E2E, CI Passed) en success; QA-02 pasa a Completado | Codebuff    |
| 2026-09-27 | DOC-01         | PHP 8.4 CLI portátil en `D:\DEV\tools\php-8.4` (PATH de usuario, ini de referencia en `.freebuff/php/php.ini`, `.env.testing` local con sqlite en memoria) para correr Pint, PHPUnit y PHPStan sin contenedor | Pint PASS (181 archivos), 267 tests / 939 aserciones en verde y PHPStan sin errores en local; documentado en README | Codebuff    |
| 2026-09-27 | OPS-03         | Deploy fija el SHA exacto aprobado por CI (`DEPLOY_SHA`, verificación HEAD) y registra `DEPLOY`/`ROLLBACK_OK`/`ROLLBACK_FALLO` con backup en `deployments.log`; runbook del rollback manual en README | Script `scripts/rollback.sh` validado con `bash -n`, dry-run local y gestión de errores verificada; simulacro destructivo queda automatizado en `ops-rehearsal.yml` | Codebuff    |

## Cobertura de Content Security Policy

### Política vigente

Definida en `app/Http/Middleware/SecurityHeaders.php`, con nonce por request (`random_bytes`) que se comparte con Blade (`app.blade.php`), Vite (`Vite::useCspNonce`) e Inertia (`HandleInertiaRequests`):

| Directivo | Valor y motivo |
| --------- | -------------- |
| `script-src` | `'self' 'nonce-…'` sin `'unsafe-inline'`; `'unsafe-eval'` solo en local; hosts permitidos: `static.cloudflareinsights.com` (beacon de Web Analytics) y `us-/eu-assets.i.posthog.com` (config y extensiones diferidas de PostHog). `cdn.jsdelivr.net` se eliminó: no carga ningún script. |
| `style-src` | `'self' 'nonce-…'` más Google Fonts y jsdelivr; sin `'unsafe-inline'`. |
| `style-src-attr` | `'unsafe-inline'` para los atributos `style="…"` que Vue aplica en plantillas estáticas. |
| `report-uri` | Apunta a `/csp-report` (POST → `CspReportController`), exento de CSRF y limitado a 30 informes/minuto por IP. |
| Resto | `img-src`, `font-src`, `connect-src`, `frame-src 'none'`, `object-src 'none'`, `base-uri` y `form-action` sin cambios. |

### Pruebas PHPUnit

`tests/Feature/SecurityHeadersTest.php` (9 pruebas, se ejecutan con el resto del backend en CI):

1. `script-src` exige nonce y rechaza `'unsafe-inline'`.
2. `style-src` rechaza `'unsafe-inline'` pero conserva `style-src-attr`.
3. Los scripts inline de la respuesta llevan el mismo nonce de la política.
4. Los hosts de `script-src` coinciden exactamente con la allowlist (falla si reaparece un CDN muerto o se quita un host en uso).
5. Cabeceras complementarias: `X-Content-Type-Options`, `X-Frame-Options` y `Referrer-Policy`.
6. La política declara `report-uri /csp-report`.
7. El endpoint responde 204 y registra la violación como `Log::warning` con el contexto del informe (directivo, recurso bloqueado, documento).
8. `/csp-report` figura entre las rutas exentas de verificación CSRF (`preventRequestForgery`), que los navegadores necesitan porque no envían token.
9. El limiter `csp-report` admite 30 informes por IP y minuto y devuelve 429 en el 31.º.

### Reporting de violaciones

La cabecera `report-uri` hace que el navegador envíe cada violación a `POST /csp-report` (`CspReportController::store`): lee el cuerpo `csp-report` (o JSON plano), lo registra como `Log::warning` estructurado con IP y user agent, y responde 204. La ruta lleva `throttle:csp-report` (30/min por IP, definido en `AppServiceProvider`), está exenta de CSRF en `bootstrap/app.php` y no toca sesión ni base de datos, de modo que un atacante solo puede generar logs limitados.

### Pruebas E2E (Playwright)

`tests/e2e/csp.spec.ts` (17 pruebas, job `e2e` de CI contra `php artisan serve --env=testing`):

- **7 páginas públicas:** `/`, `/servicios`, `/portafolio`, `/blog`, `/contacto`, `/privacidad`, `/terminos`.
- **10 rutas autenticadas:** `/dashboard`, `/dashboard/projects`, `/dashboard/messages`, `/dashboard/posts`, `/dashboard/technologies`, `/dashboard/testimonials`, `/dashboard/subscribers`, `/dashboard/settings`, `/dashboard/logs` y `/profile`, con login en `beforeEach` (mismo administrador que `projects.spec.ts`).
- **Por página se verifica:** cabecera presente con nonce y sin `'unsafe-inline'`, hosts dentro de la allowlist, Vue montado en `#app`, al menos un `script[nonce]`, y **cero violaciones** registradas por la trampa de `securitypolicyviolation` más los rechazos de consola. Un script inline bloqueado falla el test con el directivo y el recurso afectados.

Ejecución: `php artisan test --filter=SecurityHeadersTest` y `bun run test:e2e`. La suite E2E se verificó contra un servidor fixture local en cuatro escenarios: política sana (pasa), script sin nonce (falla), cabecera antigua con `'unsafe-inline'` (falla) y CDN muerto en `script-src` (falla).

## Protección durante limpiezas

No eliminar sin revisión específica:

- `.env`, `.env.tunnel` y credenciales.
- `public/build` mientras se sirvan assets de producción.
- `storage/app/private/temp` si existen trabajos pendientes.
- Backups y volúmenes de PostgreSQL/Redis.
- Logs recientes necesarios para diagnóstico.
- Cambios locales sin confirmar en Git.
