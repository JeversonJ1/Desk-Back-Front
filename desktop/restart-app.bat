@echo off
echo.
echo ========================================
echo   REINICIANDO APLICACAO ELECTRON
echo ========================================
echo.

REM Encontrar e matar processos npm e electron
echo [1/3] Parando processos antigos...
taskkill /F /IM electron.exe 2>nul
taskkill /F /IM node.exe /FI "WINDOWTITLE eq npm*" 2>nul
timeout /t 2 /nobreak >nul

echo [2/3] Limpando cache...
if exist node_modules\.cache rmdir /s /q node_modules\.cache
timeout /t 1 /nobreak >nul

echo [3/3] Iniciando aplicacao...
echo.
echo ----------------------------------------
echo   Aplicacao iniciando...
echo   Aguarde a janela abrir
echo ----------------------------------------
echo.

start cmd /k "npm start"

echo.
echo ========================================
echo   REINICIO CONCLUIDO!
echo ========================================
echo.
echo Os handlers foram registrados novamente.
echo A sincronizacao agora deve funcionar.
echo.
pause
