@echo off
echo Deteniendo servidor CantCome...
taskkill /f /im php.exe /fi "WindowTitle eq *artisan*" 2>nul
echo Servidor detenido.
pause
