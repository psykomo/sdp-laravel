<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;

it('allows authenticated users to list inmates', function (): void {
    $user = User::factory()->create();
    Inmate::factory()->count(2)->create();

    $this->actingAs($user)
        ->getJson(route('inmate-registry.inmates.index'))
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 2);
});

it('allows authenticated users to create inmates', function (): void {
    $user = User::factory()->create();

    $payload = [
        'full_name' => 'Budi Santoso',
        'inmate_number' => 'N-2026-0001',
        'gender' => InmateGender::Male->value,
        'birth_date' => '1990-02-10',
        'nationality' => 'Indonesia',
    ];

    $this->actingAs($user)
        ->postJson(route('inmate-registry.inmates.store'), $payload)
        ->assertCreated()
        ->assertJsonPath('data.inmate_number', 'N-2026-0001')
        ->assertJsonPath('data.gender', InmateGender::Male->value);

    $this->assertDatabaseHas('inmates', [
        'inmate_number' => 'N-2026-0001',
        'full_name' => 'Budi Santoso',
        'gender' => InmateGender::Male->value,
    ]);
});

it('rejects invalid inmate payloads', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson(route('inmate-registry.inmates.store'), [
            'full_name' => '',
            'inmate_number' => 'bad number',
            'gender' => 'unknown',
            'birth_date' => now()->addDay()->toDateString(),
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['full_name', 'inmate_number', 'gender', 'birth_date']);
});

it('blocks guests from inmate endpoints', function (): void {
    $this->getJson(route('inmate-registry.inmates.index'))
        ->assertUnauthorized();
});
