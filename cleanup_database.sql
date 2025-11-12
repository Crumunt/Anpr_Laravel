-- SQL script to fix MySQL tablespace issue
-- Run this script in MySQL command line or MySQL Workbench

-- Step 1: Drop the database (this may fail if directory is not empty, that's okay)
DROP DATABASE IF EXISTS `anpr`;

-- Step 2: If the above fails, manually delete the folder:
-- Windows: Usually in C:\ProgramData\MySQL\MySQL Server X.X\Data\anpr
-- Or check: SHOW VARIABLES LIKE 'datadir';

-- Step 3: Create fresh database
CREATE DATABASE `anpr` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 4: Use the database
USE `anpr`;

