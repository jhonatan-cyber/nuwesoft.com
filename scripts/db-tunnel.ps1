# =============================================================
#  db-tunnel.ps1 - Tunel SSH hacia PostgreSQL de produccion
#  Uso:
#    .\scripts\db-tunnel.ps1 start   # abre el tunel en segundo plano
#    .\scripts\db-tunnel.ps1 status  # verifica si esta activo
#    .\scripts\db-tunnel.ps1 test    # prueba conexion por el tunel
#    .\scripts\db-tunnel.ps1 stop    # cierra el tunel
#
#  Requiere en .env.tunnel (o variables de entorno):
#    DEV_SSH_HOST=ip-o-dominio-del-vps
#    DEV_SSH_USER=usuario
#    DEV_SSH_PORT=22              # opcional (default 22)
#    DEV_SSH_KEY=ruta\a\clave     # opcional si usa agente/clave default
# =============================================================
param(
    [Parameter(Position = 0)]
    [ValidateSet('start', 'stop', 'status', 'test')]
    [string]$Action = 'start'
)

$ErrorActionPreference = 'Stop'

# -- Configuracion --
$LocalPort = 15432
$RemoteBindHost = '127.0.0.1'   # pgsql escucha solo en localhost del VPS
$RemotePort = 15432             # 5432 del VPS esta ocupado por un postgres nativo
$PidFile = Join-Path $PSScriptRoot '.db-tunnel.pid'
$ErrFile = Join-Path $PSScriptRoot '.db-tunnel.err'

function Read-DotEnvLocal {
    $envFile = Join-Path (Split-Path $PSScriptRoot -Parent) '.env.tunnel'
    if (-not (Test-Path $envFile)) { return @{} }
    $map = @{}
    Get-Content $envFile | ForEach-Object {
        if ($_ -match '^\s*([A-Z0-9_]+)\s*=\s*(.*)\s*$') {
            $map[$Matches[1]] = $Matches[2].Trim('"', "'")
        }
    }
    return $map
}

$dotenv = Read-DotEnvLocal
function Get-Cfg([string]$Name, [string]$Default = '') {
    $fromEnv = [Environment]::GetEnvironmentVariable($Name)
    if ($fromEnv) { return $fromEnv }
    if ($dotenv.ContainsKey($Name)) { return $dotenv[$Name] }
    return $Default
}

$SshHost = Get-Cfg 'DEV_SSH_HOST'
$SshUser = Get-Cfg 'DEV_SSH_USER'
$SshPort = Get-Cfg 'DEV_SSH_PORT' '22'
$SshKey = Get-Cfg 'DEV_SSH_KEY'

if (-not $SshHost -or -not $SshUser) {
    Write-Host "[X] Faltan DEV_SSH_HOST / DEV_SSH_USER en .env.tunnel" -ForegroundColor Red
    exit 1
}

function Get-TunnelPid {
    if (-not (Test-Path $PidFile)) { return $null }
    $tunnelPid = Get-Content $PidFile -ErrorAction SilentlyContinue
    if ($tunnelPid -and (Get-Process -Id $tunnelPid -ErrorAction SilentlyContinue)) { return $tunnelPid }
    Remove-Item $PidFile -ErrorAction SilentlyContinue
    return $null
}

switch ($Action) {
    'status' {
        $tunnelPid = Get-TunnelPid
        if ($tunnelPid) {
            Write-Host "[OK] Tunel activo (PID $tunnelPid) - localhost:$LocalPort -> ${SshHost}:$RemotePort" -ForegroundColor Green
        } else {
            Write-Host "[--] Tunel inactivo" -ForegroundColor Yellow
        }
    }

    'stop' {
        $tunnelPid = Get-TunnelPid
        if ($tunnelPid) {
            Stop-Process -Id $tunnelPid -Force
            Remove-Item $PidFile -ErrorAction SilentlyContinue
            Write-Host "[OK] Tunel cerrado (PID $tunnelPid)" -ForegroundColor Green
        } else {
            Write-Host "[--] No hay tunel activo" -ForegroundColor Yellow
        }
    }

    'test' {
        try {
            $client = New-Object Net.Sockets.TcpClient
            $client.Connect('127.0.0.1', $LocalPort)
            $client.Close()
            Write-Host "[OK] Puerto $LocalPort accesible - tunel OK" -ForegroundColor Green
        } catch {
            Write-Host "[X] No se puede conectar a 127.0.0.1:$LocalPort - hay tunel activo?" -ForegroundColor Red
            exit 1
        }
    }

    'start' {
        if (Get-TunnelPid) {
            Write-Host "[--] El tunel ya esta activo (localhost:$LocalPort)" -ForegroundColor Yellow
            exit 0
        }

        $sshArgs = @(
            '-N'
            '-o', 'ExitOnForwardFailure=yes'
            '-o', 'ServerAliveInterval=30'
            '-o', 'ServerAliveCountMax=3'
            '-o', 'StrictHostKeyChecking=accept-new'
            '-p', $SshPort
            '-L', "${LocalPort}:${RemoteBindHost}:${RemotePort}"
        )
        if ($SshKey) { $sshArgs += @('-i', $SshKey) }
        $sshArgs += "$SshUser@$SshHost"

        $proc = Start-Process -FilePath 'ssh' -ArgumentList $sshArgs `
            -WindowStyle Hidden -PassThru `
            -RedirectStandardError $ErrFile

        Start-Sleep -Seconds 3

        if ($proc.HasExited) {
            Write-Host "[X] SSH salio con codigo $($proc.ExitCode). Ver: $ErrFile" -ForegroundColor Red
            exit 1
        }

        Set-Content -Path $PidFile -Value $proc.Id
        Write-Host "[OK] Tunel abierto: 127.0.0.1:$LocalPort -> ${SshHost}:${RemotePort} (PID $($proc.Id))" -ForegroundColor Green
        Write-Host "     Configura en .env:  DB_HOST=127.0.0.1  DB_PORT=$LocalPort"
        Write-Host "     Para cerrar:  .\scripts\db-tunnel.ps1 stop"
    }
}
