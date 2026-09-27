#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────
#  Nuwesoft — Rollback verificado de despliegue (OPS-03)
#
#  Uso en el VPS:
#    ./scripts/rollback.sh [--dry-run] [--skip-db] [commit]
#
#  Comportamiento:
#  - Por defecto revierte al despliegue anterior registrado en
#    deployments.log. Con un argumento, revierte a ese commit.
#  - Restaura la base de datos desde el backup creado inmediatamente
#    antes del despliegue actual (registrado en deployments.log), hace
#    checkout del commit objetivo, reconstruye contenedores y verifica
#    el healthcheck local.
#  - --dry-run muestra el plan sin ejecutar nada destructivo.
#  - --skip-db omite la restauración de base de datos (solo si el
#    despliegue revertido no contenía migraciones destructivas).
#  - Todo paso y su resultado se añaden a deployments.log.
# ──────────────────────────────────────────────────────────────
set -euo pipefail

COMPOSE="docker compose -f compose.prod.yaml"
APP_DIR="${APP_DIR:-/var/www/nuwesoft.com}"
LOG_FILE="${APP_DIR}/deployments.log"
HEALTH_RETRIES="${HEALTH_RETRIES:-12}"
HEALTH_SLEEP="${HEALTH_SLEEP:-5}"
SKIP_DB=0
DRY_RUN=0
TARGET=""

for arg in "$@"; do
  case "$arg" in
    --dry-run) DRY_RUN=1 ;;
    --skip-db) SKIP_DB=1 ;;
    -h|--help) sed -n '2,20p' "$0"; exit 0 ;;
    *) TARGET="$arg" ;;
  esac
done

log() { echo "[$(date -u +%Y-%m-%dT%H:%M:%SZ)] $*" | tee -a "$LOG_FILE"; }

die() { log "ERROR: $*"; exit 1; }

# ── Cargar el historial de despliegues ──────────────────────────
# Formato de línea: <timestamp>|DEPLOY|<sha>|<backup>
#                   <timestamp>|ROLLBACK_OK|<sha>|<backup>
if [ ! -f "$LOG_FILE" ]; then
  die "No existe $LOG_FILE: no hay historial de despliegues para revertir."
fi

last_deploy_line=$(grep -E "\|DEPLOY\|" "$LOG_FILE" | tail -1 || true)
[ -n "$last_deploy_line" ] || die "deployments.log no contiene despliegues registrados."

current_sha=$(echo "$last_deploy_line" | cut -d'|' -f3)
last_backup=$(echo "$last_deploy_line" | cut -d'|' -f4)

# El objetivo es el último commit desplegado distinto del actual.
if [ -z "$TARGET" ]; then
  TARGET=$(grep -E "\|DEPLOY\|" "$LOG_FILE" | awk -F'|' '$3 != "'"${current_sha}"'" {sha=$3} END {print sha}')
  [ -n "$TARGET" ] || die "No hay un despliegue anterior en el historial; indica un commit explícito."
fi

echo "====================================================="
echo " Despliegue actual : ${current_sha:0:9}"
echo " Commit objetivo   : ${TARGET:0:9}"
echo " Backup previo     : ${last_backup:-<ninguno>}"
echo " Base de datos     : $([ "$SKIP_DB" = 1 ] && echo 'SIN restaurar (--skip-db)' || echo 'se restaurará')"
echo " Modo              : $([ "$DRY_RUN" = 1 ] && echo 'DRY-RUN' || echo 'APLICAR')"
echo "====================================================="

if [ "$DRY_RUN" = 1 ]; then
  echo "Plan:"
  echo " 1. git -C '$APP_DIR' fetch origin && git checkout '$TARGET'"
  echo " 2. $COMPOSE up -d --build --remove-orphans"
  echo " 3. $([ "$SKIP_DB" = 1 ] && echo '(omitido)' || echo "$COMPOSE exec -T web php artisan backup:restore '$last_backup' --force")"
  echo " 4. $COMPOSE exec -T web php artisan optimize:clear && config:cache && route:cache && view:cache"
  echo " 5. Healthcheck local (hasta $HEALTH_RETRIES intentos)"
  echo " 6. Registrar ROLLBACK_OK en $LOG_FILE"
  exit 0
fi

cd "$APP_DIR"

# 0. Verify the target commit is reachable.
git fetch origin --quiet || die "git fetch falló"
git cat-file -e "$TARGET^{commit}" 2>/dev/null || die "El commit $TARGET no existe en el repositorio."

# 1. Checkout del commit objetivo.
log "ROLLBACK inicio: ${current_sha:0:9} → ${TARGET:0:9} (backup: ${last_backup:-ninguno})"
git checkout "$TARGET" --quiet || die "git checkout $TARGET falló"

# 2. Reconstruir contenedores con el código objetivo.
$COMPOSE up -d --build --remove-orphans || die "docker compose up --build falló"

# 3. Restaurar base de datos desde el backup previo al despliegue revertido.
if [ "$SKIP_DB" = 1 ]; then
  log "AVISO: --skip-db: la base de datos NO se restaura; el esquema puede no coincidir con el código."
elif [ -n "$last_backup" ] && [ "$last_backup" != "-" ]; then
  $COMPOSE exec -T web php artisan backup:restore "$last_backup" --force \
    || die "La restauración de la base de datos falló; el código ya está revertido pero los datos no. Revisar manualmente."
else
  die "No hay backup registrado para el despliegue actual; usa --skip-db solo si es seguro."
fi

# 4. Reconstruir cachés.
$COMPOSE exec -T web php artisan optimize:clear || true
$COMPOSE exec -T web php artisan config:cache 2>/dev/null || true
$COMPOSE exec -T web php artisan route:cache 2>/dev/null || true
$COMPOSE exec -T web php artisan view:cache 2>/dev/null || true

# 5. Healthcheck.
retries=$HEALTH_RETRIES
until [ $retries -eq 0 ] || $COMPOSE exec -T web curl -sf http://localhost/ >/dev/null 2>&1; do
  retries=$((retries - 1))
  echo "Esperando healthcheck... ($retries intentos restantes)"
  sleep "$HEALTH_SLEEP"
done
[ $retries -gt 0 ] || { log "ROLLBACK_FALLO: healthcheck sin respuesta tras revertir a ${TARGET:0:9}. Intervención manual."; exit 1; }

# 6. Éxito verificado.
log "ROLLBACK_OK|${TARGET}|${last_backup:--}"
echo "====================================================="
echo " ✅ Rollback verificado: aplicación sana en ${TARGET:0:9}"
echo " Historial: $LOG_FILE"
echo "====================================================="
