# App Startup Flow

This document describes how to start the application in local development.

## Prerequisites

- PHP 8.2+ with required extensions
- Composer
- Node.js + npm
- Docker + Docker Compose

## First-time setup

1. Install PHP dependencies:
   - `composer install`
2. Install frontend dependencies:
   - `npm install`
3. Copy environment file if needed:
   - `cp .env.example .env`
4. Generate application key:
   - `php artisan key:generate`
5. Start database services:
   - `docker compose up -d`
6. Run migrations:
   - `php artisan migrate`

## Daily startup

1. Start Docker services:
   - `docker compose up -d`
2. Start app services (Laravel server, queue worker, logs, Vite):
   - `composer run dev`
3. Open app:
   - `http://localhost:8011`

## Database admin UI

- URL: `http://localhost:8080`
- System: `MariaDB`
- Server: `mariadb`
- Username: `laravel` (or `root`)
- Password: `laravel` (or `root`)
- Database: `sdp`

## Stop services

- Stop app dev processes: `Ctrl + C`
- Stop containers:
  - `docker compose down`

## Troubleshooting

- If app cannot connect to DB, verify:
  - `docker compose ps`
  - `.env` uses:
    - `DB_CONNECTION=mysql`
    - `DB_HOST=127.0.0.1`
    - `DB_PORT=3306`
    - `DB_DATABASE=sdp`
    - `DB_USERNAME=laravel`
    - `DB_PASSWORD=laravel`
- If frontend changes are not reflected:
  - Ensure `composer run dev` (or `npm run dev`) is running.
- If schema changes include primary key type changes (for example `bigint` to `uuid`):
  - Use `php artisan migrate:fresh` in local development.
  - If using Dockerized MariaDB, an alternative clean reset is:
    - `docker compose down -v`
    - `docker compose up -d`
    - `php artisan migrate`
