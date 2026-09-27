# Nuwesoft

Sitio público y panel administrativo de Nuwesoft, construido con Laravel, Inertia, Vue y PostgreSQL. Las imágenes se almacenan en Cloudinary y las capturas automáticas utilizan Chromium.

## Requisitos

- Docker Desktop con Docker Compose.
- Git y PowerShell 7 en Windows.
- Node.js/npm y Bun para ejecutar herramientas frontend fuera del contenedor.
- PHP 8.4 CLI local (opcional, para Pint/PHPUnit sin contenedor; ver abajo).
- Acceso SSH al VPS solamente cuando se necesite consultar la base remota.

Nunca confirmes `.env`, `.env.tunnel`, claves SSH ni credenciales de Cloudinary en Git.

## Desarrollo local

1. Copia `.env.example` como `.env` y configura `APP_KEY`, puerto y servicios.
2. Levanta el sistema:

    ```powershell
    docker compose up -d --build
    docker compose exec laravel.test php artisan key:generate
    docker compose exec laravel.test php artisan migrate
    ```

3. Abre `http://localhost:${APP_PORT}`. En la configuración actual se usa normalmente `http://localhost:8080`.
4. Comprueba el estado público:

    ```powershell
    Invoke-RestMethod http://localhost:8080/health
    ```

El endpoint público solo muestra `status` y `timestamp`. Las métricas internas requieren una sesión administrativa.

Comandos habituales:

```powershell
docker compose ps
docker compose logs -f laravel.test
docker compose exec laravel.test php artisan optimize:clear
docker compose exec laravel.test php artisan queue:failed
```

## Base remota mediante túnel SSH

Usa la base remota únicamente cuando el trabajo lo requiera. Para pruebas automatizadas utiliza siempre una base aislada.

1. Crea `.env.tunnel`:

    ```dotenv
    DEV_SSH_HOST=servidor
    DEV_SSH_USER=usuario
    DEV_SSH_PORT=22
    DEV_SSH_KEY=C:\ruta\a\clave_privada
    ```

2. Inicia y verifica el túnel:

    ```powershell
    .\scripts\db-tunnel.ps1 start
    .\scripts\db-tunnel.ps1 status
    .\scripts\db-tunnel.ps1 test
    ```

3. Configura la aplicación con `DB_HOST=host.docker.internal` y `DB_PORT=15432` cuando Laravel se ejecuta dentro de Docker.
4. Cierra el túnel al terminar:

    ```powershell
    .\scripts\db-tunnel.ps1 stop
    ```

## Pruebas y análisis

```powershell
docker compose exec -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e CACHE_STORE=array -e SESSION_DRIVER=array -e QUEUE_CONNECTION=sync laravel.test php artisan test
docker compose exec laravel.test vendor/bin/pint --test
docker compose exec laravel.test vendor/bin/phpstan analyse --no-progress --memory-limit=512M
bun run test
bun run lint
bun run build
bun audit
```

El primer comando fuerza SQLite en memoria. No ejecutes la suite heredando las variables del túnel porque podría intentar conectarse a PostgreSQL remoto.

### PHP local sin contenedor (Pint, PHPUnit, PHPStan)

Hay un PHP 8.4 CLI portátil (la misma versión mayor que usa CI) en `D:\DEV\tools\php-8.4`, añadido al PATH de usuario. No requiere instalador ni admin. Si hay que reproducirlo en otra máquina:

1. Descarga «PHP 8.4 NTS Win32 vs17 x64» de windows.php.net y descomprímelo en `D:\DEV\tools\php-8.4`.
2. Copia el ini de referencia del proyecto: `cp .freebuff/php/php.ini D:\DEV\tools\php-8.4\php.ini` (extensiones `pdo_sqlite`, `mbstring`, `gd`, `intl`, etc.; ctype/dom/xml ya van embebidas en el binario).
3. Añade la carpeta al PATH de usuario y abre una terminal nueva.

Con `vendor/` ya instalado, `php` no necesita Composer para las herramientas: el check de plataforma de Composer solo exige ≥ 8.3.2. Los comandos equivalentes sin contenedor:

```powershell
php vendor/bin/pint --test                                   # estilo (lo mismo que el hook pre-commit)
php artisan test --env=testing                               # suite completa
php vendor/bin/phpstan analyse --no-progress --memory-limit=512M
```

`php artisan test --env=testing` usa el `.env.testing` local (sqlite en memoria, cola sync, broadcast null); el archivo está en `.gitignore`, se genera con `cp .env.example .env.testing` ajustando `APP_ENV`/`DB_*` y requiere `php artisan key:generate --env=testing` la primera vez. Es necesario `.env.testing` porque Laravel carga `.env` en el proceso de `artisan test` y las variables del túnel ganarían a los `<env>` de `phpunit.xml`.

`bun.lock` es el único lockfile de JavaScript: CI, `Makefile`, `dev.ps1` y `Dockerfile.prod` usan Bun, y `package-lock.json` está excluido del repositorio. Las versiones parcheadas de dependencias transitivas se fijan en `overrides` dentro de `package.json`.

Estado de `bun audit`: una única excepción conocida, `stream-json@1.9.1` (dependencia de `@crawlee/core`, sin versión 1.x corregida y con la 3.x incompatible con su ruta de importación). No es alcanzable desde el navegador y solo se usa para leer JSON de almacenamiento local generado por el propio crawlee.

Las pruebas E2E están en `tests/e2e`. El flujo CI crea una base PostgreSQL vacía, un administrador temporal y ejecuta Playwright sin conectarse a producción.

## Content Security Policy

Toda respuesta emite una CSP con un nonce generado por petición en `app/Http/Middleware/SecurityHeaders.php`, que se comparte con Blade (`resources/views/app.blade.php`), con Vite y con las páginas Inertia (`HandleInertiaRequests`).

- `script-src` y `style-src` exigen `'self'` y `'nonce-…'`: `'unsafe-inline'` ya no está permitido.
- `style-src-attr` mantiene `'unsafe-inline'` porque Vue aplica los atributos `style="…"` escritos en las plantillas.
- `script-src` solo admite `https://static.cloudflareinsights.com` (beacon de Web Analytics de Cloudflare) y `https://us-assets.i.posthog.com` / `https://eu-assets.i.posthog.com` (config y extensiones diferidas de PostHog). Cualquier otro host externo queda fuera: `cdn.jsdelivr.net` se eliminó porque solo servía imágenes.
- `'unsafe-eval'` y los hosts de `localhost` aparecen únicamente en el entorno `local`, donde el compilador de vue-i18n necesita eval.
- `frame-src 'none'`, `object-src 'none'`, `base-uri 'self'` y `form-action 'self'` restringen marcos, objetos, bases y envíos de formulario.
- `report-uri /csp-report` reenvía las violaciones a `CspReportController` (exento de CSRF en `bootstrap/app.php` y limitado a 30 informes/minuto por IP); cada informe se registra como `Log::warning` estructurado y responde 204.

Al añadir un script inline nuevo hay que pasarle el nonce: Blade y Vite lo reciben solos, el JSON-LD de Vue usa el composable `useCspNonce()` y cualquier `<style>` inline debe llevar el nonce (o compilarse desde un SFC). Si rompes esta regla, lo detectan las pruebas de abajo.

### Pruebas de la CSP

| Archivo | Cobertura | Cómo ejecutarla |
| ------- | --------- | --------------- |
| `tests/Feature/SecurityHeadersTest.php` (9 pruebas) | Nonce presente, `unsafe-inline` ausente, scripts de la respuesta con el mismo nonce, allowlist exacta de hosts de `script-src` y reporting: `report-uri` en la cabecera, endpoint a 204 con log estructurado, exclusión de CSRF y rate limit. | `php artisan test --filter=SecurityHeadersTest` |
| `tests/e2e/csp.spec.ts` (17 pruebas) | 7 páginas públicas y 10 rutas del dashboard autenticado: cabecera con nonce, Vue montado en `#app`, `script[nonce]` presente y cero violaciones. | `bun run test:e2e` |

El E2E instala una trampa de `securitypolicyviolation` y de consola antes de que se ejecute ningún script de la página; si un script inline se bloquea, el test falla indicando el directivo y el recurso afectados. En CI corre en el job `Playwright E2E` contra `php artisan serve --env=testing`.

## Capturas y Cloudinary

- Producción instala Chromium en la imagen y define `CHROME_PATH=/usr/bin/chromium`.
- Cada petición del capturador bloquea redes privadas, protocolos inseguros y cambios de origen.
- Las subidas usan cola con tres intentos y backoff.
- El dashboard muestra `pending`, `processing`, `completed` o `failed` para cada proyecto.
- Revisa fallos con `php artisan queue:failed` y reintenta con `php artisan queue:retry <id>`.

## Backups

Crear un respaldo comprimido:

```powershell
docker compose exec laravel.test php artisan backup:database --compress
```

Los respaldos de producción viven en el volumen `backups_data`, fuera del ciclo de vida del contenedor web. Verifica siempre que el archivo tenga contenido y que `gzip -t` finalice correctamente.

Restaurar reemplaza por completo el esquema actual. Hazlo primero en staging y utiliza solamente archivos dentro de `storage/app/backups`:

```powershell
docker compose exec laravel.test php artisan backup:restore backup_FECHA.sql.gz --force
```

No ejecutes una restauración manual en producción mientras la aplicación acepte escrituras.

## Despliegue y rollback

El workflow `CI` ejecuta PHPUnit, Pint, PHPStan, ESLint, Vitest, build y Playwright. `Deploy to VPS` solo se inicia cuando CI termina correctamente en `main`.

El despliegue:

1. Genera y valida un backup persistente.
2. Descarga el commit aprobado por CI.
3. reconstruye contenedores y ejecuta migraciones.
4. verifica el healthcheck.
5. ante un fallo, restaura el backup, vuelve al commit anterior y comprueba nuevamente la aplicación.

Después de un rollback revisa GitHub Actions, `docker compose -f compose.prod.yaml ps`, los logs del contenedor web y la tabla `failed_jobs`.

## Seguimiento

El estado, criterios de aceptación y registro de cada mejora se mantienen en [docs/PLAN_MEJORAS_SISTEMA.md](docs/PLAN_MEJORAS_SISTEMA.md).
