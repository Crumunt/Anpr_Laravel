@echo off
echo ========================================
echo MySQL Tablespace Fix for Laravel
echo ========================================
echo.

REM Get database credentials from .env or use defaults
echo This script will help fix the MySQL tablespace issue.
echo.
echo IMPORTANT: You need to run this as Administrator if MySQL service needs to be stopped.
echo.

REM Try to find MySQL in PATH
where mysql >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo MySQL command line client not found in PATH.
    echo Please add MySQL bin directory to your PATH or provide full path.
    echo.
    echo Common locations:
    echo   C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe
    echo   C:\xampp\mysql\bin\mysql.exe
    echo   C:\wamp\bin\mysql\mysql*\bin\mysql.exe
    echo   C:\laragon\bin\mysql\mysql*\bin\mysql.exe
    echo.
    set /p MYSQL_PATH="Enter full path to mysql.exe (or press Enter to skip): "
    if "%MYSQL_PATH%"=="" (
        echo.
        echo Please run the following SQL commands manually in MySQL:
        echo   DROP DATABASE IF EXISTS anpr;
        echo   CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
        echo.
        echo Then stop MySQL service, delete the folder: [MySQL Data Directory]\anpr
        echo Then start MySQL service and run: php artisan migrate:fresh --seed
        pause
        exit /b 1
    )
) else (
    set MYSQL_PATH=mysql
)

echo.
echo Step 1: Finding MySQL data directory...
echo Run this in MySQL: SHOW VARIABLES LIKE 'datadir';
echo.

echo Step 2: Attempting to fix database...
echo Please enter your MySQL root password when prompted.
echo.

REM Try to connect and fix (user will need to provide password)
echo Attempting to drop and recreate database...
echo You will be prompted for MySQL password.
echo.

%MYSQL_PATH% -u root -p -e "DROP DATABASE IF EXISTS anpr; CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Database recreated successfully!
    echo You can now run: php artisan migrate:fresh --seed
) else (
    echo.
    echo Could not fix database automatically.
    echo.
    echo Manual fix required:
    echo 1. Open MySQL command line or MySQL Workbench
    echo 2. Run: DROP DATABASE IF EXISTS anpr;
    echo 3. If that fails, find MySQL data directory:
    echo    SHOW VARIABLES LIKE 'datadir';
    echo 4. Stop MySQL service: net stop MySQL
    echo 5. Delete folder: [data directory]\anpr
    echo 6. Start MySQL service: net start MySQL
    echo 7. Run: CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    echo 8. Run: php artisan migrate:fresh --seed
)

echo.
pause

