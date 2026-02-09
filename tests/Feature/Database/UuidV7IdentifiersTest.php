<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Ramsey\Uuid\Uuid;

it('generates uuid v7 identifiers for users and wbp', function (): void {
    $user = User::factory()->create();
    $wbp = Wbp::factory()->create();

    expect(Uuid::isValid((string) $user->id))->toBeTrue();
    expect(Uuid::isValid((string) $wbp->id))->toBeTrue();

    expect(Uuid::fromString((string) $user->id)->getVersion())->toBe(7);
    expect(Uuid::fromString((string) $wbp->id)->getVersion())->toBe(7);
});
