# ──────────────────────────────────────────────────
#  Nuwesoft.com — PowerShell Dev Commands
#  Usage: .\dev.ps1 <command>
# ──────────────────────────────────────────────────
param(
    [Parameter(Position=0)]
    [string]$Command = "help"
)

$DC = "docker compose"
$APP = "laravel.test"
$EXEC = "$DC exec $APP"
$ARTISAN = "$EXEC php artisan"

function Show-Help {
    Write-Host @"

  Nuwesoft.com Docker Dev Commands
  -------------------------------

  Setup:
    setup           Full setup (migrate + seed)
    fresh-setup     Drop DB, migrate, seed

  Containers:
    up              Start all services
    down            Stop all services
    restart         Restart Laravel container
    rebuild         Full rebuild (--build)
    ps              Show container status

  Database:
    migrate         Run pending migrations
    seed            Run database seeders
    fresh           Drop + migrate + seed
    rollback        Rollback last migration
    tinker          Open Laravel Tinker

  Dependencies:
    composer        composer install
    node            bun install + fix perms

  Quality:
    test            Run PHPUnit tests
    pint            Check code style
    pint-fix        Auto-fix code style
    stan            PHPStan static analysis
    lint            pint + stan

  Frontend:
    dev             Start Vite dev server
    build           Build production assets

  Debugging:
    logs            Tail Laravel logs
    logs-all        Tail all container logs
    shell           Open bash in container
    db              Open PostgreSQL shell
    redis           Open Redis CLI

  Other:
    cache-clear     Clear all caches
    cache-optimize  Optimize all caches
    ownership       Fix file permissions
    verify          Check everything works

"@ -ForegroundColor Cyan
}

switch ($Command) {
    "help"          { Show-Help }
    "setup"         { Invoke-Expression "$DC up -d"; Invoke-Expression "$EXEC bun install"; Invoke-Expression "$EXEC chown -R sail:sail /var/www/html/node_modules"; Invoke-Expression "$EXEC composer install"; Invoke-Expression "$ARTISAN key:generate --force"; Invoke-Expression "$ARTISAN migrate --force"; Invoke-Expression "$ARTISAN db:seed --force"; Write-Host "`n✅ Setup complete" -ForegroundColor Green }
    "fresh-setup"   { Invoke-Expression "$DC up -d"; Invoke-Expression "$EXEC bun install"; Invoke-Expression "$EXEC chown -R sail:sail /var/www/html/node_modules"; Invoke-Expression "$EXEC composer install"; Invoke-Expression "$ARTISAN key:generate --force"; Invoke-Expression "$ARTISAN migrate:fresh --force"; Invoke-Expression "$ARTISAN db:seed --force"; Write-Host "`n✅ Fresh setup complete" -ForegroundColor Green }
    "up"            { Invoke-Expression "$DC up -d"; Invoke-Expression "$DC ps" }
    "down"          { Invoke-Expression "$DC down" }
    "restart"       { Invoke-Expression "$DC restart $APP" }
    "rebuild"       { Invoke-Expression "$DC down"; Invoke-Expression "$DC up -d --build"; Start-Sleep 10; Invoke-Expression "$DC ps" }
    "stop"          { Invoke-Expression "$DC stop" }
    "ps"            { Invoke-Expression "$DC ps" }
    "migrate"       { Invoke-Expression "$ARTISAN migrate --force" }
    "seed"          { Invoke-Expression "$ARTISAN db:seed --force" }
    "fresh"         { Invoke-Expression "$ARTISAN migrate:fresh --force"; Invoke-Expression "$ARTISAN db:seed --force"; Write-Host "`n✅ Database reset and seeded" -ForegroundColor Green }
    "rollback"      { Invoke-Expression "$ARTISAN migrate:rollback --force" }
    "tinker"        { Invoke-Expression "$EXEC php artisan tinker" }
    "composer"      { Invoke-Expression "$EXEC composer install" }
    "node"          { Invoke-Expression "$EXEC bun install"; Invoke-Expression "$EXEC chown -R sail:sail /var/www/html/node_modules"; Write-Host "`n✅ node_modules installed" -ForegroundColor Green }
    "test"          { Invoke-Expression "$ARTISAN test" }
    "pint"          { Invoke-Expression "$EXEC ./vendor/bin/pint" }
    "pint-fix"      { Invoke-Expression "$EXEC ./vendor/bin/pint --fix" }
    "stan"          { Invoke-Expression "$EXEC ./vendor/bin/phpstan analyse --memory-limit=512M" }
    "lint"          { Invoke-Expression "$EXEC ./vendor/bin/pint"; Invoke-Expression "$EXEC ./vendor/bin/phpstan analyse --memory-limit=512M"; Write-Host "`n✅ Quality checks passed" -ForegroundColor Green }
    "dev"           { Invoke-Expression "$EXEC bun run dev" }
    "build"         { Invoke-Expression "$EXEC bun run build" }
    "logs"          { Invoke-Expression "$DC logs -f $APP" }
    "logs-all"      { Invoke-Expression "$DC logs -f" }
    "shell"         { Invoke-Expression "$EXEC bash" }
    "db"            { Invoke-Expression "$EXEC psql -U postgres -d nuwesoft" }
    "redis"         { Invoke-Expression "$EXEC redis-cli -h redis" }
    "cache-clear"   { Invoke-Expression "$ARTISAN cache:clear"; Invoke-Expression "$ARTISAN config:clear"; Invoke-Expression "$ARTISAN route:clear"; Invoke-Expression "$ARTISAN view:clear"; Write-Host "`n✅ All caches cleared" -ForegroundColor Green }
    "cache-optimize"{ Invoke-Expression "$ARTISAN config:cache"; Invoke-Expression "$ARTISAN route:cache"; Invoke-Expression "$ARTISAN view:cache"; Write-Host "`n✅ Caches optimized" -ForegroundColor Green }
    "ownership"     { Invoke-Expression "$EXEC chown -R sail:sail /var/www/html"; Write-Host "`n✅ File ownership fixed" -ForegroundColor Green }
    "verify"        { Invoke-Expression "$DC ps"; Write-Host "`n── HTTP Check ──"; Invoke-Expression "curl -s -o /dev/null -w '  localhost -> HTTP %{http_code}' http://localhost:8088/" }
    default         { Write-Host "Unknown command: $Command" -ForegroundColor Red; Show-Help }
}
