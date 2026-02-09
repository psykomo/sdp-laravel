# ADR 0001: Modular Monolith With Extraction-Oriented Boundaries

- Status: Accepted
- Date: 2026-02-09
- Deciders: Engineering team

## Context

The system will evolve across multiple prison-domain capabilities (inmate registry, admission, sentencing, movement, visits, release, etc.).

Starting directly with microservices would add operational complexity too early (distributed transactions, observability, deployment orchestration, service contracts, failure modes).

We need:

- fast feature delivery now,
- strong module boundaries now,
- and low migration cost to microservices later.

## Decision

Adopt a **modular monolith** in Laravel with strict module boundaries.

Each module follows:

- `Domain`
- `Application`
- `Infrastructure`
- `Interfaces`
- `Database`

Application bootstraps module providers from `config/modules.php` via `App\Providers\ModuleServiceProvider`.

Modules expose behavior through typed contracts and route/controllers in their own namespace. Modules should avoid direct table access into other modules.

## Consequences

### Positive

- Keeps deployment and local development simple.
- Preserves clear boundaries needed for later extraction.
- Enables incremental module-by-module delivery.
- Improves maintainability through typed interfaces/DTOs.

### Negative

- Requires discipline to prevent accidental cross-module coupling.
- Shared process/database can hide future distributed-system concerns if boundaries are ignored.

## Guardrails

- Use strict types (`declare(strict_types=1);`) in module PHP code.
- Use enums/value objects for constrained domains.
- Use Form Requests for validation.
- Keep repository interfaces in `Application/Contracts`; implementation in `Infrastructure`.
- Prefer stable public IDs (`ULID/UUID`) for externally referenced aggregates.
- Write Pest tests per module.

## Implementation Notes

Reference implementation exists in `InmateRegistry` module.

New module scaffolding is automated using:

- `php artisan make:module <ModuleName>`

This command creates the standard module layout and registers the module provider in `config/modules.php`.
