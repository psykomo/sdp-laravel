# Local Database (MariaDB via Docker Compose)

## Current setup

- Database engine: MariaDB 11.8
- Main DB name: `sdp`
- App DB connection: MySQL driver (Laravel)
- DB admin UI: Adminer

## Compose services

Defined in `/Users/hap/Documents/dev/sdp/sdp-laravel/docker-compose.yml`:

- `mariadb`
  - `MARIADB_DATABASE=sdp`
  - `MARIADB_USER=laravel`
  - `MARIADB_PASSWORD=laravel`
  - Port: `3306`
- `adminer`
  - Port: `8080`
  - Depends on healthy `mariadb`

## Laravel env config

Set in:
- `/Users/hap/Documents/dev/sdp/sdp-laravel/.env`
- `/Users/hap/Documents/dev/sdp/sdp-laravel/.env.example`

Values:

- `DB_CONNECTION=mysql`
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=sdp`
- `DB_USERNAME=laravel`
- `DB_PASSWORD=laravel`

## SymmetricDS replication note

- Replicated tables include a dedicated `version` column used by our replication flow.
- Baseline column type is `unsignedBigInteger` with default `1`.
- Ensure new tables also include `version` for consistency across hub-and-spoke nodes.

## Run locally

1. `docker compose up -d`
2. `php artisan migrate`

## Open DB admin

- URL: `http://localhost:8080`
- System: `MariaDB`
- Server: `mariadb`
- Username: `laravel` (or `root`)
- Password: `laravel` (or `root`)
- Database: `sdp`

## Reset clean state

If you changed DB name or need fresh init:

1. `docker compose down -v`
2. `docker compose up -d`
3. `php artisan migrate`
