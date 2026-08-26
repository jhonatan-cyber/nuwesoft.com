# Nuwesoft

Sitio público y panel administrativo de Nuwesoft, construido con Laravel, Inertia, Vue y PostgreSQL. Las imágenes se almacenan en Cloudinary y las capturas automáticas utilizan Chromium.

## Requisitos

- Docker Desktop con Docker Compose.
- Git y PowerShell 7 en Windows.
- Node.js/npm y Bun para ejecutar herramientas frontend fuera del contenedor.
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
```

El primer comando fuerza SQLite en memoria. No ejecutes la suite heredando las variables del túnel porque podría intentar conectarse a PostgreSQL remoto.

Las pruebas E2E están en `tests/e2e`. El flujo CI crea una base PostgreSQL vacía, un administrador temporal y ejecuta Playwright sin conectarse a producción.

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
