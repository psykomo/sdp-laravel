<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Ramsey\Uuid\Uuid;

it('generates uuid v7 identifiers for users and inmates', function (): void {
    $user = User::factory()->create();
    $inmate = Inmate::factory()->create();

    expect(Uuid::isValid((string) $user->id))->toBeTrue();
    expect(Uuid::isValid((string) $inmate->id))->toBeTrue();

    expect(Uuid::fromString((string) $user->id)->getVersion())->toBe(7);
    expect(Uuid::fromString((string) $inmate->id)->getVersion())->toBe(7);
});
