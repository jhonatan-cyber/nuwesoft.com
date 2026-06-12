@echo off
title Nuwesoft Dev
chcp 65001 >nul
echo Starting Nuwesoft development environment...
echo.

:: ── Kill stale processes on Vite ports ──
echo [0/4] Cleaning stale processes...
node scripts/kill-stale-ports.mjs
echo.

echo [1/4] Detecting LAN IP...
node scripts/lan-ip.mjs
echo.

:: ── Detect LAN IP ──
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4"') do (
    set "LAN_IP=%%a"
    goto :ip_found
)
:ip_found
for /f "tokens=* delims= " %%b in ("%LAN_IP%") do set "LAN_IP=%%b"

if defined LAN_IP (
    echo.
    echo   ╔══════════════════════════════════════════════════╗
    echo   ║     🌐  Red Local                               ║
    echo   ║                                                ║
    echo   ║     Accede desde tu celular u otros             ║
    echo   ║     dispositivos en la misma red:               ║
    echo   ║                                                ║
    echo   ║     🔗  http://%LAN_IP%:8080                     ║
    echo   ║     ⚡  Vite HMR: http://%LAN_IP%:%VITE_PORT%            ║
    echo   ║                                                ║
    echo   ╚══════════════════════════════════════════════════╝
    echo.
) else (
    echo   ╔══════════════════════════════════════════════════╗
    echo   ║     ⚠️  No se detecto IP LAN                    ║
    echo   ║     Conectate a una red WiFi/LAN                ║
    echo   ╚══════════════════════════════════════════════════╝
    echo.
)

echo [2/4] Starting Laravel server on http://127.0.0.1:8080
start "Laravel Server" cmd /c "cd /d %~dp0 && php artisan serve --port=8080"

echo [3/4] Starting Queue worker for async 404 events
start "Queue Worker" cmd /c "cd /d %~dp0 && php artisan queue:work --queue=default --tries=3 --sleep=3"

echo [4/4] Starting Vite dev server (hot reload)
if not defined VITE_DEV_PORT set VITE_DEV_PORT=5173
set "VITE_PORT=%VITE_DEV_PORT%"
start "Vite Dev" cmd /c "cd /d %~dp0 && bun run dev --port=%VITE_PORT%"

echo.
echo All services started in separate windows.
echo.
pause
