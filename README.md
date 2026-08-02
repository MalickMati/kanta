# 🚀 Complete Laravel Application & Microservices Documentation

Welcome to the all-in-one guide for setting up, configuring, running, and managing this Laravel application along with its auxiliary Node.js microservices (`scale_service` and `printer_service`) on both **Linux (Ubuntu/Debian)** and **Windows** operating systems.

---

## 📋 Table of Contents
1. [Linux Installation Guide (Ubuntu / Debian)](#-linux-installation-guide-ubuntu--debian)
   - [1. System Update & Dependencies](#1-system-update--dependencies)
   - [2. Install PHP & Required Extensions](#2-install-php--required-extensions)
   - [3. Install MySQL Server & Database Setup](#3-install-mysql-server--database-setup)
   - [4. Install Composer](#4-install-composer)
   - [5. Install Node.js & NPM](#5-install-nodejs--npm)
   - [6. Additional Tools for Microservices (CUPS, Chrome & Serial Access)](#6-additional-tools-for-microservices-cups-chrome--serial-access)
2. [Windows Installation Guide](#-windows-installation-guide)
   - [Option A: Using XAMPP / WAMP](#option-a-using-xampp--wamp)
   - [Option B: Using Standalone Drivers & Services](#option-b-using-standalone-drivers--services)
3. [Laravel Main Application Setup](#-laravel-main-application-setup)
4. [Microservices Setup & Configuration](#-microservices-setup--configuration)
   - [A. Scale Microservice (`scale_service`)](#a-scale-microservice-scale_service)
   - [B. Printer Microservice (`printer_service`)](#b-printer-microservice-printer_service)
   - [C. PM2 Process Manager Setup (Recommended for Production)](#c-pm2-process-manager-setup-recommended-for-production)
5. [Useful Artisan & Management Commands](#-useful-artisan--management-commands)
6. [Complete `.env` File Environment Variables Documentation](#-complete-env-file-environment-variables-documentation)

---

## 🐧 Linux Installation Guide (Ubuntu / Debian)

Execute the following commands in order to set up a fresh Linux machine.

### 1. System Update & Dependencies
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget unzip git software-properties-common ca-certificates lsb-release build-essential
```

### 2. Install PHP & Required Extensions
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

# Create database and user
sudo mysql -u root -e "CREATE DATABASE kanta_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -u root -e "CREATE USER 'kanta_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';"
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON kanta_laravel.* TO 'kanta_user'@'localhost';"
sudo mysql -u root -e "FLUSH PRIVILEGES;"
```

### 4. Install Composer
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
composer --version
```

### 5. Install Node.js & NPM
```bash
# Install Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

### 6. Additional Tools for Microservices (CUPS, Chrome & Serial Access)
- **Scale Service Requirements**: Grant current user permission to access serial COM/USB ports (`dialout` group).
- **Printer Service Requirements**: Install Google Chrome and CUPS (`lp` print command).

```bash
# Grant user access to serial hardware ports (/dev/ttyUSB*, /dev/ttyS*)
sudo usermod -a -G dialout $USER

# Install CUPS printing system and Google Chrome for PDF/HTML rendering
sudo apt install -y cups cups-client
wget https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
sudo dpkg -i google-chrome-stable_current_amd64.deb || sudo apt --fix-broken install -y

# Restart your user session or reboot for group changes to take effect:
# sudo reboot
```

---

## 🪟 Windows Installation Guide

### Option A: Using XAMPP / WAMP
1. **Install XAMPP** (with PHP 8.2+): [Apache Friends Website](https://www.apachefriends.org/).
2. **Start Services**: Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. **Install Composer**: Download from [GetComposer.org](https://getcomposer.org/Composer-Setup.exe).
4. **Install Node.js**: Download LTS installer from [Nodejs.org](https://nodejs.org/).
5. **Create Database**: Open `http://localhost/phpmyadmin` and create database `kanta_laravel`.

### Option B: Using Standalone Drivers & Services
1. Install PHP for Windows from [windows.php.net](https://windows.php.net/download/). Enable extensions: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `gd`, `fileinfo`.
2. Install **MySQL Installer for Windows**.
3. Install **Composer** & **Node.js**.
4. Configure Printer in Windows Settings and ensure default printer is designated.

---

## 🛠️ Laravel Main Application Setup

Navigate to your root project folder (`Kanta_Laravel`):

### 1. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Frontend assets & build
npm install
npm run build
```

### 2. Environment Setup & Application Key
```bash
# Linux
cp .env.example .env

# Windows (CMD)
copy .env.example .env

# Generate Key
php artisan key:generate
```

### 3. Database Migration & Storage Link
```bash
php artisan migrate --seed
php artisan storage:link
```

*For Linux permissions:*
```bash
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 4. Run Laravel Server
```bash
php artisan serve
```
App will be accessible at: `http://127.0.0.1:8000`

---

## 🔌 Microservices Setup & Configuration

This system utilizes two standalone Node.js microservices:
1. **Scale Service (`scale_service`)**: Reads real-time weight measurements from RS232/USB serial weighing indicators and broadcasts readings via WebSockets (`Socket.io`).
2. **Printer Service (`printer_service`)**: Receives print requests (HTML/CSS) over an Express HTTP API, generates background PDF print jobs via Puppeteer/Chrome, and dispatches them silently to the default system printer using native print commands (`lp`).

---

### A. Scale Microservice (`scale_service`)

#### Purpose & Tech Stack
- **Tech Stack**: Node.js, Express, Socket.io, SerialPort, Dotenv.
- **Port**: Default `3001` (Configurable via `WS_PORT` in `.env`).
- **Functionality**: Scans and auto-detects hardware serial ports (`/dev/ttyS*`, `/dev/ttyUSB*`, COM ports), cleans raw indicator string payloads, parses numeric weight readings, and streams updates to frontend Blade views in real time.

#### Installation & Execution
```bash
# 1. Navigate to scale_service directory from project root
cd scale_service

# 2. Install dependencies
npm install

# 3. Development / Manual Run
npm start
# OR
node index.js
```

#### Environment Variables Used by Scale Service
The scale service reads settings from the parent `../.env` file:
- `WS_PORT`: WebSocket server port (Default: `3001`).
- `BAUD_RATE`: Serial communication speed (Default: `9600`).
- `DEVICE_PATH`: (Optional) Explicit serial port path (e.g., `/dev/ttyUSB0` or `COM3`). If omitted, auto-detects available serial devices.

---

### B. Printer Microservice (`printer_service`)

#### Purpose & Tech Stack
- **Tech Stack**: Node.js, Express, Puppeteer, Child Process (`lp` command).
- **Port**: Default `3000` (Configurable via `PORT` environment variable).
- **Functionality**: Accepts POST requests with raw HTML and copy counts (`/print`), renders exact print templates using headless Chrome, and sends print jobs directly to CUPS/System Printer without browser print popups.

#### Installation & Execution
```bash
# 1. Navigate to printer_service directory from project root
cd printer_service

# 2. Install dependencies
npm install

# 3. Development / Manual Run
npm start
# OR
node index.js
```

#### API Endpoint Documentation
- **Endpoint**: `POST http://localhost:3000/print`
- **Headers**: `Content-Type: application/json`
- **Body Payload**:
```json
{
  "html": "<html><body><h1>Kanta Weight Slip</h1><p>Net Weight: 5000 kg</p></body></html>",
  "quantity": 2
}
```
- **Response**:
```json
{
  "success": true,
  "message": "Print job sent successfully."
}
```

---

### C. PM2 Process Manager Setup (Recommended for Production)

To ensure the Laravel app and both Node.js microservices run continuously in the background and auto-restart on system reboot:

#### 1. Install PM2 Globally
```bash
sudo npm install -g pm2
```

#### 2. Start Services with PM2
From the root directory (`Kanta_Laravel`):

```bash
# 1. Start Scale Service
pm2 start scale_service/index.js --name "kanta-scale-service"

# 2. Start Printer Service
pm2 start printer_service/index.js --name "kanta-printer-service"

# 3. Start Laravel Queue Worker (Optional/Recommended)
pm2 start "php artisan queue:work --tries=3" --name "kanta-queue-worker"

# 4. Save PM2 configuration to launch on system boot
pm2 save
pm2 startup
```

#### PM2 Helpful Management Commands
```bash
pm2 status             # Display status of all running services
pm2 logs               # Stream real-time logs from all services
pm2 logs kanta-scale-service   # View scale service logs only
pm2 restart all        # Restart all services
pm2 stop all           # Stop all services
```

---

## ⚡ Useful Artisan & Management Commands

| Command | Description |
| :--- | :--- |
| `php artisan serve` | Starts local web development server |
| `php artisan migrate` | Runs pending database migrations |
| `php artisan migrate:fresh --seed` | Re-builds database from scratch with seed data |
| `php artisan make:controller NameController` | Generates a new controller class |
| `php artisan make:model ModelName -m` | Creates a model along with a migration file |
| `php artisan cache:clear` | Clears application cache |
| `php artisan config:clear` | Clears configuration cache |
| `php artisan route:clear` | Clears route cache |
| `php artisan view:clear` | Clears compiled Blade views |
| `php artisan optimize:clear` | Clears all cached application files |
| `php artisan queue:work` | Starts background queue processor |
| `php artisan storage:link` | Links `public/storage` to `storage/app/public` |

---

## 📘 Complete `.env` File Environment Variables Documentation

Below is the exhaustive documentation for all variables in your `.env` configuration file:

### 🌐 1. Application Configuration (`APP_*`)
- **`APP_NAME`**: Application title (e.g., `"Al Hammad Computerized Kanta"`).
- **`APP_ENV`**: Application stage (`local`, `production`, `staging`).
- **`APP_KEY`**: 32-character base64 application security key (`php artisan key:generate`).
- **`APP_DEBUG`**: Show detailed error traces (`true` for local development, `false` for production).
- **`APP_URL`**: Base URL of the app (`http://localhost` or domain name).
- **`APP_LOCALE`**: App language locale (`en`).
- **`APP_FALLBACK_LOCALE`**: Fallback language (`en`).
- **`APP_FAKER_LOCALE`**: Fake data generator region (`en_US`).
- **`APP_MAINTENANCE_DRIVER`**: Driver for maintenance state (`file` or `database`).
- **`PHP_CLI_SERVER_WORKERS`**: Workers for built-in PHP server (`4`).

### 🔐 2. Security Configuration
- **`BCRYPT_ROUNDS`**: Hashing complexity cost for password encryption (Default: `12`).

### 📜 3. Logging System (`LOG_*`)
- **`LOG_CHANNEL`**: Log handler (`stack`, `single`, `daily`, etc.).
- **`LOG_STACK`**: Channels bundled under stack logging (`single`).
- **`LOG_DEPRECATIONS_CHANNEL`**: Deprecation log target (`null`).
- **`LOG_LEVEL`**: Logging severity (`debug`, `info`, `warning`, `error`).

### 🗄️ 4. Database Configuration (`DB_*`)
- **`DB_CONNECTION`**: Database engine (`mysql`, `sqlite`, `pgsql`).
- **`DB_HOST`**: Database server IP (`127.0.0.1`).
- **`DB_PORT`**: Port number (`3306`).
- **`DB_DATABASE`**: Target database name (`kanta_laravel`).
- **`DB_USERNAME`**: Database user (`root` or custom user).
- **`DB_PASSWORD`**: Password for database authentication.

### 🔑 5. Session Management (`SESSION_*`)
- **`SESSION_DRIVER`**: Session storage driver (`database`, `file`, `redis`).
- **`SESSION_LIFETIME`**: Session timeout in minutes (`120`).
- **`SESSION_ENCRYPT`**: Encrypt session contents (`false`).
- **`SESSION_PATH`**: Session cookie path (`/`).
- **`SESSION_DOMAIN`**: Cookie domain binding (`null`).

### ⚙️ 6. System Drivers & Queues
- **`BROADCAST_CONNECTION`**: Real-time event driver (`log`, `pusher`, `ably`, `reverb`).
- **`FILESYSTEM_DISK`**: File storage system (`local`, `public`, `s3`).
- **`QUEUE_CONNECTION`**: Background queue handler (`database`, `redis`, `sync`).
- **`CACHE_STORE`**: Cache engine (`database`, `file`, `redis`).

### ⚖️ 7. Scale Microservice Configuration (`WS_*` & `BAUD_*`)
- **`WS_PORT`**: Port for WebSocket scale service (`3001`).
- **`BAUD_RATE`**: Serial port baud rate for scale communication (`9600`).
- **`DEVICE_PATH`**: Explicit device path (e.g., `/dev/ttyUSB0` or `COM3`). Auto-detected if omitted.

### 📧 8. Mail Server Configuration (`MAIL_*`)
- **`MAIL_MAILER`**: Email driver (`smtp`, `sendmail`, `log`).
- **`MAIL_SCHEME`**: Encryption scheme (`null`, `tls`, `ssl`).
- **`MAIL_HOST`**: SMTP host address (e.g., `smtp.gmail.com`).
- **`MAIL_PORT`**: Mail port (`587` for TLS / `465` for SSL).
- **`MAIL_USERNAME`**: Email sender account address.
- **`MAIL_PASSWORD`**: Email application password.
- **`MAIL_FROM_ADDRESS`**: Default sender email address.
- **`MAIL_FROM_NAME`**: Sender display name (e.g., `"Al Hamd Kanta"`).

### 🚀 9. In-Memory Stores (Redis & Memcached)
- **`MEMCACHED_HOST`**: Memcached IP (`127.0.0.1`).
- **`REDIS_CLIENT`**: Redis library (`phpredis` or `predis`).
- **`REDIS_HOST`**: Redis server address (`127.0.0.1`).
- **`REDIS_PASSWORD`**: Redis auth password (`null`).
- **`REDIS_PORT`**: Redis port (`6379`).

### ☁️ 10. AWS Cloud Storage (`AWS_*`)
- **`AWS_ACCESS_KEY_ID`**: Amazon S3 key.
- **`AWS_SECRET_ACCESS_KEY`**: Amazon S3 secret key.
- **`AWS_DEFAULT_REGION`**: AWS Region (`us-east-1`).
- **`AWS_BUCKET`**: Bucket name.
- **`AWS_USE_PATH_STYLE_ENDPOINT`**: S3 URL styling (`false`).

### 🎨 11. Frontend Asset Bundler (`VITE_*`)
- **`VITE_APP_NAME`**: Exposes application name to frontend scripts (`"${APP_NAME}"`).

### 📦 12. Backup Configuration Variables
- **`BACKUP_LOCATION`**: File system path for automated backups (e.g., `/home/m/KantaBackup` or `D:\LaravelBackups`).
- **`EMAIL_FOR_BACKUP`**: Destination email address for automated backup notification emails.

---

*Documentation generated for **Kanta Laravel Application & Microservices**.*
