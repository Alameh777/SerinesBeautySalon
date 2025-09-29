@echo off
echo Starting Laravel Application...
echo.

REM Start Apache and MySQL
echo Starting XAMPP services...
cd /d "C:\xampp"
start "" "C:\xampp\xampp-control.exe"

REM Wait a few seconds for services to start
timeout /t 5 /nobreak > nul

REM Start the Laravel application in browser
echo Opening application in browser...
start "" "http://localhost/BeautySalon/public"

echo.
echo Application is running!
echo To stop: Close this window and stop XAMPP services
echo.
pause