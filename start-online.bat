@echo off
title SMKN 13 Bandung - Online (Ngrok Static Domain)
echo ========================================================
echo   Menyalakan Web SMKN 13 Bandung ke Internet (Ngrok)...
echo ========================================================
echo.

:: 1. Pastikan MySQL XAMPP berjalan di port 3306
netstat -ano | findstr :3306 | findstr LISTENING >nul
if "%ERRORLEVEL%"=="1" (
    echo [1/3] Menyalakan MySQL...
    start "MySQL" /min "E:\xampp\mysql_start.bat"
    timeout /t 3 /nobreak >nul
) else (
    echo [1/3] MySQL sudah aktif di port 3306.
)

:: 2. Pastikan Laravel Server berjalan di port 8000
netstat -ano | findstr :8000 | findstr LISTENING >nul
if "%ERRORLEVEL%"=="1" (
    echo [2/3] Menyalakan server Laravel...
    start /B "" php artisan serve --host=127.0.0.1 --port=8000
    timeout /t 3 /nobreak >nul
) else (
    echo [2/3] Laravel server sudah aktif di port 8000.
)

echo.
echo ========================================================
echo Domain Tetap Anda (Permanen):
echo https://doorstop-antidote-sincere.ngrok-free.dev
echo ========================================================
echo.

:: 3. Jalankan Ngrok jika belum aktif
tasklist /FI "IMAGENAME eq ngrok.exe" 2>NUL | find /I /N "ngrok.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [3/3] Ngrok SUDAH AKTIF dan sedang online sekarang!
    echo.
    echo Website siap dibuka di: https://doorstop-antidote-sincere.ngrok-free.dev
    echo.
) else (
    echo [3/3] Menghubungkan Ngrok ke internet...
    .\ngrok.exe http 8000 --url=doorstop-antidote-sincere.ngrok-free.dev
)

pause
