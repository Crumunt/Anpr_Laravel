# PowerShell script to fix MySQL tablespace issue on Windows
# This script helps locate and remove the problematic database directory

Write-Host "MySQL Tablespace Fix Script" -ForegroundColor Cyan
Write-Host "============================" -ForegroundColor Cyan
Write-Host ""

# Common MySQL data directory locations on Windows
$commonPaths = @(
    "C:\ProgramData\MySQL\MySQL Server 8.0\Data",
    "C:\ProgramData\MySQL\MySQL Server 8.1\Data",
    "C:\ProgramData\MySQL\MySQL Server 8.2\Data",
    "C:\ProgramData\MySQL\MySQL Server 8.3\Data",
    "C:\ProgramData\MySQL\MySQL Server 8.4\Data",
    "C:\ProgramData\MySQL\MySQL Server 5.7\Data",
    "C:\mysql\data",
    "C:\xampp\mysql\data",
    "C:\wamp\bin\mysql\mysql*\data",
    "C:\laragon\bin\mysql\mysql*\data"
)

Write-Host "Searching for MySQL data directory..." -ForegroundColor Yellow

$foundPath = $null
foreach ($path in $commonPaths) {
    if (Test-Path $path) {
        $foundPath = $path
        Write-Host "Found MySQL data directory: $foundPath" -ForegroundColor Green
        break
    }
}

if (-not $foundPath) {
    Write-Host "Could not automatically find MySQL data directory." -ForegroundColor Red
    Write-Host ""
    Write-Host "Please run this SQL query in MySQL to find it:" -ForegroundColor Yellow
    Write-Host "SHOW VARIABLES LIKE 'datadir';" -ForegroundColor White
    Write-Host ""
    $foundPath = Read-Host "Enter the MySQL data directory path"
}

if (Test-Path "$foundPath\anpr") {
    Write-Host ""
    Write-Host "Found database directory: $foundPath\anpr" -ForegroundColor Yellow
    Write-Host ""
    $confirm = Read-Host "Do you want to delete this directory? (yes/no)"
    
    if ($confirm -eq "yes" -or $confirm -eq "y") {
        try {
            # Stop MySQL service first (requires admin rights)
            Write-Host ""
            Write-Host "Attempting to stop MySQL service..." -ForegroundColor Yellow
            try {
                Stop-Service -Name "MySQL*" -ErrorAction SilentlyContinue
                Write-Host "MySQL service stopped." -ForegroundColor Green
                Start-Sleep -Seconds 2
            } catch {
                Write-Host "Could not stop MySQL service automatically. Please stop it manually." -ForegroundColor Yellow
                Write-Host "You can stop it from Services (services.msc) or use: net stop MySQL" -ForegroundColor Yellow
                $manualStop = Read-Host "Press Enter after you have stopped MySQL service"
            }
            
            # Delete the directory
            Write-Host ""
            Write-Host "Deleting database directory..." -ForegroundColor Yellow
            Remove-Item -Path "$foundPath\anpr" -Recurse -Force
            Write-Host "Database directory deleted successfully!" -ForegroundColor Green
            
            # Start MySQL service
            Write-Host ""
            Write-Host "Starting MySQL service..." -ForegroundColor Yellow
            try {
                Start-Service -Name "MySQL*" -ErrorAction SilentlyContinue
                Write-Host "MySQL service started." -ForegroundColor Green
            } catch {
                Write-Host "Please start MySQL service manually." -ForegroundColor Yellow
            }
            
            Write-Host ""
            Write-Host "✓ Database directory removed successfully!" -ForegroundColor Green
            Write-Host "You can now run: php artisan migrate:fresh --seed" -ForegroundColor Cyan
            
        } catch {
            Write-Host ""
            Write-Host "Error: $_" -ForegroundColor Red
            Write-Host ""
            Write-Host "Manual steps:" -ForegroundColor Yellow
            Write-Host "1. Stop MySQL service" -ForegroundColor White
            Write-Host "2. Delete folder: $foundPath\anpr" -ForegroundColor White
            Write-Host "3. Start MySQL service" -ForegroundColor White
            Write-Host "4. Run: php artisan migrate:fresh --seed" -ForegroundColor White
        }
    } else {
        Write-Host "Operation cancelled." -ForegroundColor Yellow
    }
} else {
    Write-Host "Database directory not found at: $foundPath\anpr" -ForegroundColor Yellow
    Write-Host "The database may have already been cleaned up, or it's in a different location." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Alternative: Use MySQL command line" -ForegroundColor Cyan
Write-Host "Run these commands in MySQL:" -ForegroundColor Yellow
Write-Host "  DROP DATABASE IF EXISTS anpr;" -ForegroundColor White
Write-Host "  CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" -ForegroundColor White

