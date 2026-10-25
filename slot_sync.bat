@echo off
title slot_sync
cd /d D:\xampp\htdocs\fun
D:\xampp\php\php.exe public\index.php slotsync run >> D:\xampp\htdocs\fun\logs\slotsync.log 2>&1