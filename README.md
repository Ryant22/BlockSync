<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# BlockSync: Minecraft Stats SFTP

A Laravel-based tool and API for ingesting, synchronizing, and serving Minecraft player statistics from JSON files. BlockSync supports SFTP file transfer, database storage, and provides RESTful API endpoints for querying player stats, categories, and keys.

## Features
- Ingests player stats from JSON files stored locally or via SFTP
- Updates or creates player and stat records in the database
- Uses Mojang API to resolve player usernames from UUIDs
- Supports SQLite (default), MySQL, and PostgreSQL
- Console command for batch processing: `mc:ingestStats`
- SFTP sync command to fetch JSON files: `sftp:sync`
- API endpoints for querying stats, categories, and player data

## API Routes

| Method | Endpoint                                                           | Description                                                    |
|--------|--------------------------------------------------------------------|----------------------------------------------------------------|
| GET    | /api/stats/categories                                              | List all stat categories                                       |
| GET    | /api/stats/categories/{category}/keys                              | List all stat keys for a given category                        |
| GET    | /api/stats/players/{uuidOrUsername}                                | List all stats for a player by UUID or username                |
| GET    | /api/stats/players/{uuidOrUsername}/{category}                     | List all stats for a player in a specific category             |
| GET    | /api/stats/players/{uuidOrUsername}/{category}/{key}               | Get a specific stat value for a player by category and key     |

## Requirements
- PHP 8.1 or higher (with PDO and SQLite extensions)
- Composer
- Laravel 10+
- SQLite (default) or other supported DB
- XAMPP or similar local server (for Windows)

## Setup
1. **Clone the repository**
2. **Install dependencies:**
   ```sh
   composer install
   ```
3. **Copy and configure environment:**
   ```sh
   cp .env.example .env
   # Edit .env as needed (DB, SFTP, etc.)
   ```
4. **Generate application key:**
   ```sh
   php artisan key:generate
   ```
5. **Run migrations:**
   ```sh
   php artisan migrate
   ```
6. **(Optional) Seed the database:**
   ```sh
   php artisan db:seed
   ```

## Usage
- Fetch JSON files from SFTP:
  ```sh
  php artisan sftp:sync
  ```
- Run the ingestion command:
  ```sh
  php artisan mc:ingestStats
  ```
- Stats and player data will be updated in the database.

## SFTP Configuration
Set your SFTP credentials and root path in `.env`:
```
SFTP_HOST=your.sftp.host
SFTP_PORT=22
SFTP_USERNAME=youruser
SFTP_PASSWORD=yourpass
SFTP_ROOT="/path/to/stats"
SFTP_TIMEOUT=10
```

## Troubleshooting
- Ensure `pdo_sqlite` and `sqlite3` extensions are enabled in your `php.ini` if using SQLite.
- Check `storage/logs/laravel.log` for errors.
- For Mojang API issues, verify network connectivity and API limits.

## License
MIT
