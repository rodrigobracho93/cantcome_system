@echo off
cd /d "%~dp0"
echo Iniciando servidor CantCome en 127.0.0.2:8001...
echo.
wscript.exe "%~dp0start-server.vbs"
echo Servidor iniciado en segundo plano.
echo Abre http://127.0.0.2:8001 en tu navegador.
echo.
echo Para DETENER el servidor, ejecuta: stop-server.bat
pause
