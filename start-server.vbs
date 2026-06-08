' Inicia Laravel en segundo plano sin ventana de consola
CreateObject("WScript.Shell").Run "cmd /c php artisan serve --host=127.0.0.2 --port=8001", 0, False
