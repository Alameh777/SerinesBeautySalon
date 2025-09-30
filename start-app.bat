@echo off
echo Starting Apache and MySQL...

REM XAMPP installation path
set XAMPP_PATH=C:\xampp

REM Start Apache and MySQL
cd /d "%XAMPP_PATH%"
start /B apache\bin\httpd.exe
start /B mysql\bin\mysqld.exe --defaults-file=mysql\bin\my.ini

REM Wait for services to initialize
timeout /t 5 /nobreak > nul

REM Launch Laravel project in Chrome
start "" "http://localhost/BeautySalon/public"

REM Browser process to watch (Chrome)
set BROWSER_PROCESS=chrome.exe

echo.
echo Services are running. Close your browser to stop Apache & MySQL...

:wait_loop
tasklist /FI "IMAGENAME eq %BROWSER_PROCESS%" | find /I "%BROWSER_PROCESS%" > nul
if errorlevel 1 (
    goto stop_services
) else (
    timeout /t 2 /nobreak > nul
    goto wait_loop
)

:stop_services
echo Stopping Apache and MySQL...
taskkill /F /IM httpd.exe > nul 2>&1
taskkill /F /IM mysqld.exe > nul 2>&1
echo Services stopped.
exit
