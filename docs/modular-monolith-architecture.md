# Modular Monolith Architecture (Laravel 12)

This project is structured as a **modular monolith** so each module can be extracted into a microservice later with minimal refactor.

## Goals

- Keep business capabilities isolated by module (bounded context style).
- Enforce type-safe boundaries in both PHP and TypeScript.
- Avoid cross-module coupling through Eloquent internals.
- Enable future extraction to microservices via contracts and events.

## Current Foundation

### Module bootstrap

- `app/Providers/ModuleServiceProvider.php`
  - Loads module providers from config.
- `config/modules.php`
  - Single registry of enabled module providers.
- `bootstrap/providers.php`
  - Registers `App\Providers\ModuleServiceProvider::class`.

### First implemented module: InmateRegistry

- `app/Modules/InmateRegistry/Domain`
- `app/Modules/InmateRegistry/Application`
- `app/Modules/InmateRegistry/Infrastructure`
- `app/Modules/InmateRegistry/Interfaces`
- `app/Modules/InmateRegistry/Database`
- `app/Modules/InmateRegistry/InmateRegistryServiceProvider.php`

## Module Layout Standard

Every new module should follow this layout:

```text
app/Modules/<Module>/
  Domain/
  Application/
  Infrastructure/
  Interfaces/
  Database/
  <Module>ServiceProvider.php
```

### Layer responsibilities

- `Domain`: core business concepts (enums/value objects/domain rules).
- `Application`: DTOs, use cases, interfaces/contracts.
- `Infrastructure`: Eloquent models/repositories, persistence details.
- `Interfaces`: controllers, requests, routes, API/UI transport mapping.
- `Database`: module migrations/factories/seeders.

## Type-Safety Rules

### PHP

- Use `declare(strict_types=1);` in module files.
- Use explicit parameter and return types everywhere.
- Use immutable DTOs (`readonly`) for input contracts.
- Use enums for constrained values (`InmateGender`).
- Use Form Request classes for validation.
- Use interfaces for module repository boundaries.

### TypeScript

- Keep `tsconfig.json` strict (`"strict": true`, `"noImplicitAny": true`).
- Define explicit page/api types for module frontend code.
- Avoid `any`; prefer narrow unions and shared typed helpers.

## InmateRegistry Example (Implemented)

### HTTP routes

- `GET /inmate-registry/inmates` → `inmate-registry.inmates.index`
- `POST /inmate-registry/inmates` → `inmate-registry.inmates.store`

Routes are in:

- `app/Modules/InmateRegistry/Interfaces/Routes/web.php`

### Contracts and implementations

- Contract:
  - `app/Modules/InmateRegistry/Application/Contracts/InmateRepository.php`
- Eloquent implementation:
  - `app/Modules/InmateRegistry/Infrastructure/Eloquent/EloquentInmateRepository.php`
- Binding location:
  - `app/Modules/InmateRegistry/InmateRegistryServiceProvider.php`

### DTO + enum

- DTO:
  - `app/Modules/InmateRegistry/Application/DataTransferObjects/CreateInmateData.php`
- Enum:
  - `app/Modules/InmateRegistry/Domain/Enums/InmateGender.php`

### Model + data

- Eloquent model:
  - `app/Modules/InmateRegistry/Infrastructure/Models/Inmate.php`
- Migration:
  - `app/Modules/InmateRegistry/Database/Migrations/2026_02_09_074630_create_inmates_table.php`
- Factory:
  - `app/Modules/InmateRegistry/Database/Factories/InmateFactory.php`

## How to Add a New Module

1. Generate scaffold:
   - `php artisan make:module <ModuleName>`
2. Add module migrations inside `app/Modules/<Module>/Database/Migrations`.
3. Define application contracts (interfaces) first.
4. Implement infrastructure adapters (Eloquent repositories, etc.).
5. Expose endpoints via `Interfaces/Http` + `Interfaces/Routes`.
6. Add Pest feature tests under `tests/Feature/<Module>/...`.
7. Run:
   - `php artisan test --compact tests/Feature/<Module>`
   - `vendor/bin/pint --dirty --format agent`

### Generator command

- Command:
  - `php artisan make:module <ModuleName>`
- Optional flags:
  - `--force` to overwrite generated files
  - `--without-registration` to skip editing `config/modules.php`
- Stubs location:
  - `stubs/modules/*`

## Extraction Readiness (Future Microservices)

To keep extraction cost low:

- Do not access another module's tables directly.
- Interact with other modules through application contracts/events.
- Keep external transport mapping in `Interfaces` layer only.
- Prefer stable public IDs (`UUID v7`) over exposing integer IDs.
- Keep side effects explicit and queue-friendly.

## Deployment Topology (Hub and Spoke)

This architecture supports a hybrid hub-and-spoke deployment model:

- **Hub (central system/database)**:
  - Operated as the primary data center and consolidation point.
- **Spokes (branch/UPT deployments)**:
  - Some branches run with their own local database and synchronize to the hub using SymmetricDS.
  - Some branches connect directly to the central hub database/system (no local replication node).

Because both operating modes are supported, module and data design should preserve:

- deterministic record identity (`UUID v7`);
- replication metadata (`version` column on replicated tables);
- idempotent write/update behavior where synchronization may replay operations.

## UUID Strategy

- Application entities use `UUID v7` for identifiers.
- Keep in mind Laravel framework internal tables may have engine/driver assumptions.
- In particular, queue internals (`database` queue driver) still rely on their default ID behavior unless custom queue driver overrides are implemented.

## Replication Version Column

- A dedicated `version` column is required in replicated tables for SymmetricDS-based hub-and-spoke replication.
- Current baseline migration adds `version` as `unsignedBigInteger` with default `1`.
- Future tables should include `version` from the start to keep replication behavior consistent.

## Test Coverage Added

- `tests/Feature/InmateRegistry/InmateManagementTest.php`
  - list inmates (authenticated)
  - create inmate (authenticated)
  - reject invalid payload
  - block guests

## Notes

- This is the initial foundation and one reference module.
- Next module should replicate this structure to keep architecture consistent.
- Local DB setup: see `/Users/hap/Documents/dev/sdp/sdp-laravel/docs/local-database.md`.
