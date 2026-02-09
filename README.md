# Psykomo SDP Laravel - Guide

This guide covers how to manage the database and build the application for the Psykomo SDP Laravel project.

## Building the Application

### Frontend Assets (Vite)
To compile frontend resources (CSS, JS, React/Inertia):

```bash
# Install dependencies
npm install

# Build for production
npm run build
```

### Docker
To build and start the Docker containers:

```bash
# Build and start in detached mode
docker compose up -d --build
```


## Prerequisites

Ensure you have your environment set up. If you are using Docker, prefix commands with `docker compose exec app`.

## Running Migrations

Migrations create the database tables structure.

### Standard Migration
To run all pending migrations:

```bash
# Local
php artisan migrate

# Docker
docker compose exec app php artisan migrate
```

### Fresh Migration
To drop all tables and re-run all migrations (WARNING: This wipes all data):

```bash
# Local
php artisan migrate:fresh

# Docker
docker compose exec app php artisan migrate:fresh
```

## Running Seeders

Seeders populate the database with initial data (e.g., admin user, default settings).

### Standard Seeding
To run the database seeders:

```bash
# Local
php artisan db:seed

# Docker
docker compose exec app php artisan db:seed
```

### Migrate and Seed
To wipe the database, migrate, and seed in one go:

```bash
# Local
php artisan migrate:fresh --seed

# Docker
docker compose exec app php artisan migrate:fresh --seed
```

## Troubleshooting

### "Class ... not found"
If you encounter class not found errors, try dumping the autoloader:

```bash
composer dump-autoload
```

### "Duplicate Entry"
If you get a duplicate entry error (e.g., for `admin@sdp.local`), it means the data already exists.
- The `DatabaseSeeder` has been updated to check for existing users before creating them.
- If issues persist with other data, consider running `migrate:fresh --seed` to start clean.

### Issue Vite Build
```bash
docker compose exec app npm run build
```