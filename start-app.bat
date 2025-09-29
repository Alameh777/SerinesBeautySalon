@echo off
echo Starting Apache and MySQL...

REM Start Apache
cd /d "C:\xampp"
start /B apache\bin\httpd.exe

REM Start MySQL
start /B mysql\bin\mysqld.exe --defaults-file=mysql\bin\my.ini

REM Wait for services to initialize
timeout /t 5 /nobreak > nul

REM Open browser
start "" "http://localhost/BeautySalon/public"

echo.
echo Services are running in background
echo Close this window to stop them
pause

REM Stop services when user closes
taskkill /F /IM httpd.exe
taskkill /F /IM mysqld.exe