# Fix MySQL Tablespace Error (Error 1813)

## Problem
When running `php artisan migrate:fresh --seed`, you encounter:
```
SQLSTATE[HY000]: General error: 1813 Tablespace for table '`anpr`.`migrations`' exists. 
Please DISCARD the tablespace before IMPORT
```

This happens when MySQL has orphaned tablespace files (.ibd files) that can't be removed through SQL commands alone.

## Solution Options

### Option 1: Manual Fix (Recommended)

1. **Find MySQL Data Directory**
   - Open MySQL command line or MySQL Workbench
   - Run: `SHOW VARIABLES LIKE 'datadir';`
   - Note the path (usually something like `C:\ProgramData\MySQL\MySQL Server 8.0\Data`)

2. **Stop MySQL Service**
   - Open Services (`services.msc`) or run in PowerShell (as Administrator):
     ```powershell
     net stop MySQL
     ```
   - Or stop it from Services panel

3. **Delete Database Directory**
   - Navigate to the data directory from step 1
   - Delete the `anpr` folder completely
   - This removes all orphaned tablespace files

4. **Start MySQL Service**
   ```powershell
   net start MySQL
   ```

5. **Create Fresh Database**
   - Open MySQL command line:
     ```sql
     CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```

6. **Run Migrations**
   ```bash
   php artisan migrate:fresh --seed
   ```

### Option 2: Use PowerShell Script

Run the PowerShell script (requires Administrator rights):
```powershell
.\fix_mysql_tablespace.ps1
```

This script will:
- Automatically find MySQL data directory
- Stop MySQL service
- Delete the problematic database directory
- Start MySQL service
- Guide you through creating the database

### Option 3: Use Batch File

Run the batch file:
```cmd
fix_database.bat
```

This will attempt to use MySQL command line to fix the issue.

### Option 4: SQL Only (May Not Work)

If the directory is locked, this won't work, but you can try:

```sql
DROP DATABASE IF EXISTS anpr;
CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

If this fails with "Directory not empty" error, you must use Option 1.

## Quick Fix Commands

### PowerShell (Run as Administrator)
```powershell
# Stop MySQL
net stop MySQL

# Delete database directory (replace path with your MySQL data directory)
Remove-Item "C:\ProgramData\MySQL\MySQL Server 8.0\Data\anpr" -Recurse -Force

# Start MySQL
net start MySQL

# Then create database in MySQL:
# CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### MySQL Command Line
```sql
-- Connect to MySQL
mysql -u root -p

-- Drop and recreate database
DROP DATABASE IF EXISTS anpr;
CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## After Fix

Once the database is recreated, run:
```bash
php artisan migrate:fresh --seed
```

## Prevention

This issue typically occurs when:
- MySQL crashes during table creation
- Improper shutdown of MySQL
- File system issues
- Disk space issues

To prevent:
- Always stop MySQL service properly
- Ensure adequate disk space
- Regular database backups

## Common MySQL Data Directory Locations

- **MySQL Installer**: `C:\ProgramData\MySQL\MySQL Server X.X\Data`
- **XAMPP**: `C:\xampp\mysql\data`
- **WAMP**: `C:\wamp\bin\mysql\mysqlX.X\data`
- **Laragon**: `C:\laragon\bin\mysql\mysqlX.X\data`

## Need Help?

If none of these solutions work:
1. Check MySQL error logs
2. Verify MySQL service is running
3. Check file permissions on data directory
4. Ensure you have Administrator rights
5. Verify disk space is available

