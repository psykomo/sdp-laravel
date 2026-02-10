# SDP Laravel

Inmate management system foundation for Direktorat Jenderal Pemasyarakatan Republik Indonesia, built with Laravel 12 + Inertia React in a modular-monolith style.

Detailed documentation: [docs/README.md](docs/README.md)

## Tech Stack

- PHP 8.5, Laravel 12
- Inertia.js v2 + React + TypeScript
- MariaDB 11
- Docker Compose (app, web, vite, mariadb, adminer)

## Quick Start

See the startup guide: [docs/startup-flow.md](docs/startup-flow.md)

## Environment Variables (Important)

- `APP_URL`: host-run Laravel URL (for `composer run dev` / `php artisan serve`)
- `HOST_VITE_PORT`: host Vite port when running `composer run dev`
- `DB_PORT`: use `3306` with current Docker Compose

## Useful Commands

```bash
vendor/bin/pint --dirty --format agent
php artisan test --compact
```

## Architecture & Docs

- Documentation index: [docs/README.md](docs/README.md)
- Modular monolith architecture: [docs/modular-monolith-architecture.md](docs/modular-monolith-architecture.md)
- ADR 0001: [docs/adr/0001-modular-monolith.md](docs/adr/0001-modular-monolith.md)
- Local database setup: [docs/local-database.md](docs/local-database.md)
- Startup flow: [docs/startup-flow.md](docs/startup-flow.md)
