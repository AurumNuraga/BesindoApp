# BesindoApp

A modern Laravel 12 web application built with Blade templating, Tailwind CSS, and Vite for fast development and production builds.

## 📋 Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Development](#development)
- [Available Scripts](#available-scripts)
- [Project Structure](#project-structure)
- [Configuration](#configuration)
- [Database](#database)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

BesindoApp is a Laravel-based application designed for modern web development. It leverages the latest Laravel 12 framework features combined with Tailwind CSS for styling and Vite for efficient asset bundling.

**Repository**: [AurumNuraga/BesindoApp](https://github.com/AurumNuraga/BesindoApp)

## 🛠 Tech Stack

| Component | Version | Purpose |
|-----------|---------|---------|
| **Laravel** | ^12.0 | Backend framework |
| **PHP** | ^8.2 | Server-side language |
| **Blade** | Latest | Templating engine (60.2% of codebase) |
| **Tailwind CSS** | ^3.4 | Utility-first CSS framework |
| **Vite** | ^7.0 | Fast build tool & dev server |
| **Excel Processing** | maatwebsite/excel ^3.1 | Excel file handling |
| **Node.js** | Latest | Frontend tooling & package management |

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** 8.2 or higher
- **Composer** (PHP dependency manager)
- **Node.js** and npm (for frontend assets)
- **Git**
- **SQLite** (or another supported database)

### Optional
- **Docker** (for containerized development)
- **Laravel Sail** (Docker development environment)

## 🚀 Installation

### Quick Setup

Run the automated setup script:

```bash
composer setup
```

This will:
1. Install PHP dependencies
2. Copy `.env.example` to `.env` (if not exists)
3. Generate the application encryption key
4. Run database migrations
5. Install Node dependencies
6. Build frontend assets

### Manual Setup

If you prefer step-by-step installation:

```bash
# 1. Clone the repository
git clone https://github.com/AurumNuraga/BesindoApp.git
cd BesindoApp

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Create database file (SQLite)
touch database/database.sqlite

# 6. Run migrations
php artisan migrate

# 7. Install Node dependencies
npm install

# 8. Build frontend assets
npm run build
```

## 💻 Development

### Start Development Server

Run the concurrent development server with all services:

```bash
composer dev
```

This starts:
- Laravel development server (`php artisan serve`)
- Queue listener (`php artisan queue:listen`)
- Log streaming (`php artisan pail`)
- Vite dev server (`npm run dev`)

### Access Your Application

- **Application**: http://localhost:8000
- **Vite HMR**: http://localhost:5173

## 📜 Available Scripts

### Backend Scripts

```bash
# Run tests with config clearing
composer test

# Development (all-in-one)
composer dev

# Setup (initial installation)
composer setup
```

### Frontend Scripts

```bash
# Development server with hot reload
npm run dev

# Build for production
npm run build
```

## 📁 Project Structure

```
BesindoApp/
├── app/                    # Application code
│   ├── Http/              # Controllers, middleware, requests
│   ├── Models/            # Eloquent models
│   └── Services/          # Business logic
├── database/
│   ├── migrations/        # Database migrations
│   ├── seeders/           # Database seeders
│   ├── factories/         # Model factories for testing
│   └── database.sqlite    # SQLite database
├── public/                # Public assets (compiled)
├── resources/
│   ├── views/            # Blade templates
│   ├── css/              # Tailwind CSS
│   └── js/               # JavaScript/TypeScript
├── routes/               # API and web routes
├── tests/                # Test suites
├── bootstrap/            # Framework bootstrapping
├── config/               # Configuration files
├── storage/              # File storage, logs
├── composer.json         # PHP dependencies
├── package.json          # Node dependencies
├── tailwind.config.js    # Tailwind configuration
├── vite.config.js        # Vite configuration
└── .env.example          # Environment template
```

## ⚙️ Configuration

### Environment Variables

Copy `.env.example` to `.env` and configure as needed:

```env
# Application
APP_NAME=BesindoApp
APP_DEBUG=true
APP_URL=http://localhost

# Database (SQLite by default)
DB_CONNECTION=sqlite

# Change to MySQL if needed:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=besindo_app
# DB_USERNAME=root
# DB_PASSWORD=

# Cache & Session
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Database Configuration

**Default**: SQLite (file-based, no external service required)

**Switch to MySQL**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=besindo_app
DB_USERNAME=root
DB_PASSWORD=your_password
```

Then run migrations:
```bash
php artisan migrate
```

## 🗄️ Database

### Run Migrations

```bash
php artisan migrate
```

### Rollback Migrations

```bash
php artisan migrate:rollback
```

### Fresh Migration (Reset Database)

```bash
php artisan migrate:fresh
```

### Seed Database

```bash
php artisan db:seed
```

## 🧪 Testing

Run the test suite:

```bash
composer test
```

This command:
- Clears cached configuration
- Runs PHPUnit tests

### Run Specific Test

```bash
php artisan test tests/Feature/YourTest.php
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

---

## 🔗 Quick Links

- [Laravel Documentation](https://laravel.com/docs)
- [Blade Template Engine](https://laravel.com/docs/blade)
- [Tailwind CSS](https://tailwindcss.com)
- [Vite Guide](https://vitejs.dev)
- [Maatwebsite Excel](https://docs.laravel-excel.com)

## 📞 Support

For issues or questions:
- Check [GitHub Issues](https://github.com/AurumNuraga/BesindoApp/issues)
- Review [Laravel Documentation](https://laravel.com/docs)

---

**Last Updated**: May 5, 2026
