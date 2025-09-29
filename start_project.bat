@echo off
cd /d "%~dp0"
start "" "C:\laragon\laragon.exe"
timeout /t 5 >nul
start "" "http://BeautySalon.test"
exit
