# 🚀 Complete Laravel Application Documentation & Setup Guide

Welcome to the comprehensive guide for setting up, configuring, and running this Laravel project on both **Linux (Ubuntu/Debian)** and **Windows** operating systems.

---

## 📋 Table of Contents
1. [Linux Installation Guide (Ubuntu / Debian)](#-linux-installation-guide-ubuntu--debian)
   - [1. Update & System Dependencies](#1-update--system-dependencies)
   - [2. Install PHP & Required Extensions](#2-install-php--required-extensions)
   - [3. Install MySQL Server & Database Setup](#3-install-mysql-server--database-setup)
   - [4. Install Composer](#4-install-composer)
   - [5. Install Node.js & NPM](#5-install-nodejs--npm)
2. [Windows Installation Guide](#-windows-installation-guide)
   - [Option A: Using XAMPP / WAMP](#option-a-using-xampp--wamp)
   - [Option B: Using Composer & Node.js Directly](#option-b-using-composer--nodejs-directly)
3. [Laravel Application Setup](#-laravel-application-setup)
   - [Step 1: Clone or Open Project](#step-1-clone-or-open-project)
   - [Step 2: Install PHP Dependencies](#step-2-install-php-dependencies)
   - [Step 3: Install Node.js Dependencies](#step-3-install-nodejs-dependencies)
   - [Step 4: Environment Configuration (`.env`)](#step-4-environment-configuration-env)
   - [Step 5: Generate Application Key](#step-5-generate-application-key)
   - [Step 6: Run Database Migrations & Seeders](#step-6-run-database-migrations--seeders)
   - [Step 7: Storage Link & Permissions](#step-7-storage-link--permissions)
   - [Step 8: Start the Local Development Server](#step-8-start-the-local-development-server)
4. [Useful Artisan & Management Commands](#-useful-artisan--management-commands)
5. [Complete `.env` File Environment Variables Documentation](#-complete-env-file-environment-variables-documentation)

---

## 🐧 Linux Installation Guide (Ubuntu / Debian)

Below are the step-by-step terminal commands to install all required software (PHP, MySQL, Composer, Node.js) on a fresh Linux server/machine.

### 1. Update & System Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget unzip git software-properties-common ca-certificates lsb-release
```

### 2. Install PHP & Required Extensions
Laravel requires PHP (version 8.2 or higher recommended) with several core extensions:
```bash
# Add Ondrej Surý PPA for latest PHP versions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 & necessary extensions
sudo apt install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-mbstring \
php8.2-xml php8.2-bcmath php8.2-curl php8.2-gd php8.2-zip php8.2-intl php8.2-sqlite3

# Verify PHP installation
php -v
```

### 3. Install MySQL Server & Database Setup
```bash
# Install MySQL
sudo apt install -y mysql-server

# Start and enable MySQL service
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL Installation (Optional but recommended)
sudo mysql_secure_installation

# Log in to MySQL console to create database & user
sudo mysql -u root -p
```
Inside the MySQL terminal prompt, execute:
```sql
CREATE DATABASE kanta_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'kanta_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';
GRANT ALL PRIVILEGES ON kanta_laravel.* TO 'kanta_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Install Composer
```bash
# Download and install Composer globally
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Verify Composer installation
composer --version
```

### 5. Install Node.js & NPM
```bash
# Add NodeSource repository for Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verify Node & NPM installation
node -v
npm -v
```

---

## 🪟 Windows Installation Guide

### Option A: Using XAMPP / WAMP
1. **Download & Install XAMPP** (with PHP 8.2+): [Apache Friends Website](https://www.apachefriends.org/)
2. **Start Services**: Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. **Install Composer**: Download and run the Windows installer from [GetComposer.org](https://getcomposer.org/Composer-Setup.exe).
4. **Install Node.js**: Download and install LTS version from [Nodejs.org](https://nodejs.org/).
5. **Create Database**: Open `http://localhost/phpmyadmin` in browser and create a new database named `kanta_laravel`.

### Option B: Using Composer & Node.js Directly
1. Download PHP for Windows from [windows.php.net](https://windows.php.net/download/) and add PHP path to environment variables (`PATH`).
2. Enable extensions in `php.ini`: `extension=pdo_mysql`, `extension=mbstring`, `extension=openssl`, `extension=curl`, `extension=fileinfo`, `extension=gd`.
3. Install **MySQL Installer for Windows** or use SQLite.
4. Install **Composer Windows Installer**.
5. Install **Node.js Windows Installer**.

---

## 🛠️ Laravel Application Setup

Execute these commands inside your project root folder (`Kanta_Laravel`).

### Step 1: Clone or Open Project
```bash
# Linux / Windows Git Bash
git clone <repository_url>
cd Kanta_Laravel
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node.js Dependencies
```bash
npm install
npm run build   # Compile assets for production
# OR
npm run dev     # Run Vite development server
```

### Step 4: Environment Configuration (`.env`)
Copy `.env.example` to `.env`:

*On Linux / macOS:*
```bash
cp .env.example .env
```

*On Windows (CMD / PowerShell):*
```cmd
copy .env.example .env
```

### Step 5: Generate Application Key
```bash
php artisan key:generate
```

### Step 6: Run Database Migrations & Seeders
```bash
php artisan migrate --seed
```

### Step 7: Storage Link & Permissions
Create symbolic link for file storage uploads:
```bash
php artisan storage:link
```

*On Linux, give proper permissions to storage & cache folders:*
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Step 8: Start the Local Development Server
```bash
php artisan serve
```
Access the application in your web browser at: `http://127.0.0.1:8000`

---

## ⚡ Useful Artisan & Management Commands

| Command | Description |
| :--- | :--- |
| `php artisan serve` | Starts local development server |
| `php artisan migrate` | Executes pending database migrations |
| `php artisan migrate:fresh --seed` | Wipes database, runs all migrations and populates seed data |
| `php artisan make:controller NameController` | Creates a new controller class |
| `php artisan make:model ModelName -m` | Creates a model along with a migration file |
| `php artisan cache:clear` | Clears application cache |
| `php artisan config:clear` | Clears configuration cache |
| `php artisan route:clear` | Clears route cache |
| `php artisan view:clear` | Clears compiled Blade view files |
| `php artisan optimize:clear` | Clears all cached items (config, route, views) |
| `php artisan queue:work` | Starts processing background queue jobs |
| `php artisan storage:link` | Creates symbolic link from `public/storage` to `storage/app/public` |

---

## 📘 Complete `.env` File Environment Variables Documentation

The `.env` file contains key-value pairs used to configure environment-specific settings without hardcoding them into application code.

### 🌐 1. Application Configuration (`APP_*`)
- **`APP_NAME`**: Name of the application (e.g., `"Al Hammad Computerized Kanta"`). Displayed in emails, UI headers, and browser titles.
- **`APP_ENV`**: Current environment stage (`local`, `testing`, `staging`, `production`). In `local` mode, error details and debugging features are enabled.
- **`APP_KEY`**: A 32-character base64 encryption key generated via `php artisan key:generate`. Used to encrypt cookies, passwords, session tokens, and sensitive data.
- **`APP_DEBUG`**: Boolean (`true` or `false`). Controls detailed exception stack trace display in browser. Set to `false` in production!
- **`APP_URL`**: Root domain/URL of the app (e.g., `http://localhost` or `https://yourdomain.com`). Used for asset links and URL generation in emails.
- **`APP_LOCALE`**: Default language code for translation files (`en`, `ur`, `es`, etc.).
- **`APP_FALLBACK_LOCALE`**: Fallback language if a translation string is missing in `APP_LOCALE`.
- **`APP_FAKER_LOCALE`**: Localization setting for PHP Faker library when seeding test data (e.g., `en_US`).
- **`APP_MAINTENANCE_DRIVER`**: Driver used when app is in maintenance mode (`file` or `database`).
- **`PHP_CLI_SERVER_WORKERS`**: Number of worker processes used by `php artisan serve` for handling concurrent requests locally.

### 🔐 2. Security Configuration
- **`BCRYPT_ROUNDS`**: Work factor cost for hashing passwords using bcrypt (default `12`). Higher values increase security but require more CPU processing time.

### 📜 3. Logging System (`LOG_*`)
- **`LOG_CHANNEL`**: Default log channel/driver (`stack`, `single`, `daily`, `stderr`, `syslog`, `slack`).
- **`LOG_STACK`**: Channels bundled under the stack logger (e.g., `single`).
- **`LOG_DEPRECATIONS_CHANNEL`**: Channel used for logging PHP deprecation warnings (`null` or `single`).
- **`LOG_LEVEL`**: Minimum severity level to log (`debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`).

### 🗄️ 4. Database Configuration (`DB_*`)
- **`DB_CONNECTION`**: Database driver type (`mysql`, `sqlite`, `pgsql`, `sqlsrv`).
- **`DB_HOST`**: Database server IP or hostname (`127.0.0.1` for local machine).
- **`DB_PORT`**: Port number for MySQL database (Default `3306`).
- **`DB_DATABASE`**: Name of the database schema (e.g., `kanta_laravel`).
- **`DB_USERNAME`**: Database user login username (e.g., `root` or `kanta_user`).
- **`DB_PASSWORD`**: Password for the database user account.

### 🔑 5. Session Management (`SESSION_*`)
- **`SESSION_DRIVER`**: Where session data is stored (`file`, `cookie`, `database`, `apc`, `memcached`, `redis`, `array`).
- **`SESSION_LIFETIME`**: Inactivity time in minutes before a session expires (default `120` minutes).
- **`SESSION_ENCRYPT`**: Encrypt session payload data before storage (`false` or `true`).
- **`SESSION_PATH`**: Cookie path validity (default `/`).
- **`SESSION_DOMAIN`**: Cookie domain scope (`null` or `.yourdomain.com`).

### ⚙️ 6. System Drivers & Queues
- **`BROADCAST_CONNECTION`**: Driver used for real-time WebSocket events (`log`, `pusher`, `ably`, `reverb`).
- **`FILESYSTEM_DISK`**: Default storage driver (`local`, `public`, `s3`).
- **`QUEUE_CONNECTION`**: Driver for asynchronous queue processing (`sync`, `database`, `redis`, `sqs`).
- **`CACHE_STORE`**: Cache storage backend (`database`, `file`, `redis`, `memcached`).

### 📧 7. Mail Server Configuration (`MAIL_*`)
- **`MAIL_MAILER`**: Email delivery engine (`smtp`, `sendmail`, `mailgun`, `ses`, `log`, `array`).
- **`MAIL_SCHEME`**: Transport encryption scheme (`tls`, `ssl`, or `null`).
- **`MAIL_HOST`**: SMTP server address (e.g., `smtp.gmail.com`).
- **`MAIL_PORT`**: SMTP port (e.g., `587` for TLS / `465` for SSL).
- **`MAIL_USERNAME`**: Email account username or SMTP address.
- **`MAIL_PASSWORD`**: Password or app-specific password for the email account.
- **`MAIL_FROM_ADDRESS`**: Default "Sender" email address shown on outgoing emails.
- **`MAIL_FROM_NAME`**: Sender name accompanying `MAIL_FROM_ADDRESS` (e.g., `"Al Hamd Kanta"`).

### 🚀 8. In-Memory Caching & Stores (Redis & Memcached)
- **`MEMCACHED_HOST`**: IP address of Memcached server (`127.0.0.1`).
- **`REDIS_CLIENT`**: Client extension used for Redis (`phpredis` or `predis`).
- **`REDIS_HOST`**: IP address of Redis server (`127.0.0.1`).
- **`REDIS_PASSWORD`**: Authentication password for Redis server (`null` if none).
- **`REDIS_PORT`**: Port number for Redis service (default `6379`).

### ☁️ 9. AWS Cloud Storage Configuration (`AWS_*`)
- **`AWS_ACCESS_KEY_ID`**: Amazon Web Services IAM access key.
- **`AWS_SECRET_ACCESS_KEY`**: AWS IAM secret access key.
- **`AWS_DEFAULT_REGION`**: AWS target region (e.g., `us-east-1`).
- **`AWS_BUCKET`**: Name of the S3 storage bucket.
- **`AWS_USE_PATH_STYLE_ENDPOINT`**: Boolean for S3 endpoint formatting.

### 🎨 10. Frontend Asset Bundler (`VITE_*`)
- **`VITE_APP_NAME`**: Exposes application name environment variable to frontend Vite/Vue/React scripts (`"${APP_NAME}"`).

### 📦 11. Custom Application Environment Variables
- **`BACKUP_LOCATION`**: Directory path on the local or external storage system where automated backups are saved (e.g., `/home/m/KantaBackup` on Linux or `D:\LaravelBackups` on Windows).
- **`EMAIL_FOR_BACKUP`**: Recipient email address designated to receive system backup alerts and reports (e.g., `malikmati7867@gmail.com`).

---

*Documentation generated for **Kanta Laravel Application**.*
