@echo off
title SMKN 13 Bandung - Stop Server
echo ========================================================
echo   Menghentikan Server Web SMKN 13 Bandung...
echo ========================================================
echo.

echo [1/3] Mematikan Ngrok Tunnel...
taskkill /F /IM ngrok.exe 2>nul

echo [2/3] Mematikan Cloudflare Tunnel...
taskkill /F /IM cloudflared.exe 2>nul

echo [3/3] Mematikan Server Laravel...
taskkill /F /IM php.exe 2>nul

echo.
echo ========================================================
echo   Server Web dan Tunnel berhasil dihentikan.
echo ========================================================
timeout /t 3 >nul
