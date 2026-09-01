@echo off
title fun_slot xservice
cd /d "%~dp0"
echo Starting fun_slot sync service...
echo Path: %CD%
echo.
D:\xampp\php\php.exe -f "%~dp0Startup.php"
echo.
echo Service stopped (exit code %ERRORLEVEL%).
pause
