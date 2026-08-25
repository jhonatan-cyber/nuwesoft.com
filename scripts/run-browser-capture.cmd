@echo off
setlocal
set "OPENSSL_CONF="
set "ELECTRON_RUN_AS_NODE="
set "NODE_OPTIONS="
"C:\Program Files\nodejs\node.exe" "%~dp0browser-capture.mjs"
exit /b %ERRORLEVEL%
