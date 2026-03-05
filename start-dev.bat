@echo off
title LMS Dev Server (PHP 8.2)
echo Starting LMS artisan serve on http://127.0.0.1:8080 ...
echo Apache proxy routes http://localhost/lms/public/ here.
echo.
cd /d C:\xampp\htdocs\lms
C:\php82\php.exe artisan serve --host=127.0.0.1 --port=8080
