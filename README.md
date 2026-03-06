# ANPR Gate Pass Management System

A comprehensive **Automatic Number Plate Recognition (ANPR)** and **Gate Pass Management System** built with Laravel. This system enables organizations to manage vehicle access control, process gate pass applications, monitor vehicle entries/exits through ANPR technology, and maintain security oversight.

## Features

### Gate Pass Management
- **Online Application Portal** - Public-facing form for gate pass applications
- **Multi-step Application Process** - Personal info, documents, vehicle details, and review
- **Document Management** - Upload and verification of required documents
- **Application Status Tracking** - Track applications through various approval stages
- **Vehicle Registration** - Register and manage multiple vehicles per applicant
- **Gate Pass Renewal** - Support for gate pass renewal workflows

### ANPR Dashboard (Security)
- **Live Vehicle Detection** - Real-time license plate recognition and logging
- **Flagged Vehicles** - Alert system for unauthorized or flagged vehicles
- **Analytics & Reports** - Traffic analytics and downloadable reports
- **Multi-Gate Support** - Monitor multiple entry/exit gates
- **Camera Integration** - Support for IP-based camera systems

### Administration
- **Role-Based Access Control** - Multiple user roles (Super Admin, Admin Editor, Admin Viewer, Encoder, Security, Maintenance)
- **Applicant Management** - View, approve, and manage applicant records
- **Vehicle Management** - Centralized vehicle database
- **Activity Logging** - Comprehensive audit trail using Spatie Activity Log
- **System Settings** - Configurable applicant types and system parameters

### Applicant Portal
- **Personal Dashboard** - View application status and registered vehicles
- **Profile Management** - Update personal information
- **Vehicle Tracking** - Monitor registered vehicles and gate pass validity
- **Activity Log** - View personal activity history

---

## Requirements

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **MySQL** >= 8.0 or **MariaDB** >= 10.4
- **Web Server** (Apache/Nginx) or use Laravel's built-in server for development

---

## Dependencies

### PHP Packages (Composer)

| Package | Version | Description |
|---------|---------|-------------|
| laravel/framework | ^12.0 | Laravel Framework |
| laravel/sanctum | ^4.0 | API Authentication |
| laravel/tinker | ^2.10.1 | REPL for Laravel |
| livewire/livewire | ^3.7 | Full-stack framework for Laravel |
| spatie/laravel-activitylog | ^4.11 | Activity logging |
| spatie/laravel-permission | ^6.20 | Role & permission management |

### Development PHP Packages

| Package | Version | Description |
|---------|---------|-------------|
| laravel/breeze | ^2.3 | Authentication scaffolding |
| laravel/pail | ^1.2.2 | Real-time log viewer |
| laravel/pint | ^1.13 | Code style fixer |
| laravel/sail | ^1.41 | Docker development environment |
| phpunit/phpunit | ^11.5.3 | Testing framework |
| mockery/mockery | ^1.6 | Mocking framework |
| fakerphp/faker | ^1.23 | Fake data generator |

### JavaScript Packages (NPM)

| Package | Version | Description |
|---------|---------|-------------|
| tailwindcss | ^4.1.11 | Utility-first CSS framework |
| alpinejs | ^3.14.9 | Lightweight JS framework |
| sweetalert2 | ^11.6.13 | Beautiful alert dialogs |
| vite | ^6.0.11 | Frontend build tool |
| laravel-vite-plugin | ^1.2.0 | Laravel Vite integration |
| axios | ^1.7.4 | HTTP client |
| autoprefixer | ^10.4.2 | CSS vendor prefixing |
| postcss | ^8.4.31 | CSS transformations |

---

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Anpr_Laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

Update the `.env` file with your settings:

```env
APP_NAME="ANPR Gate Pass System"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=anpr
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Create Database

Create a MySQL database named `anpr` (or as specified in your `.env`):

```sql
CREATE DATABASE anpr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Run Migrations

```bash
php artisan migrate
```

### 8. Seed the Database (Optional)

Populate the database with initial data (roles, permissions, sample data):

```bash
php artisan db:seed
```

### 9. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 10. Start the Development Server

Using the built-in development script (recommended):
```bash
composer dev
```

This starts the Laravel server, queue worker, log viewer, and Vite concurrently.

Or manually:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## Development

### Running Tests

```bash
php artisan test
```

Or using PHPUnit directly:
```bash
./vendor/bin/phpunit
```

### Code Formatting

```bash
./vendor/bin/pint
```

### Queue Worker

For processing background jobs:
```bash
php artisan queue:work
```

### Real-time Logs

```bash
php artisan pail
```

---

## User Roles

| Role | Description |
|------|-------------|
| `super_admin` | Full system access, manage all settings |
| `admin_editor` | Manage applicants, vehicles, and admin accounts |
| `admin_viewer` | View-only access to admin panel |
| `encoder` | Data entry and application processing |
| `security` | ANPR dashboard access |
| `security_admin` | ANPR dashboard + manage security accounts |
| `maintenance` | System maintenance access |
| `applicant` | Gate pass applicant portal access |

---

## License

This project is proprietary software.
