<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the inmate list page to authenticated users', function (): void {
    $user = User::factory()->create();
    Inmate::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('inmate-registry.inmates.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('inmate-registry/inmates/index')
            ->has('inmates.data', 2)
            ->has('inmates.meta')
            ->where('inmates.meta.last_page', 1)
        );
});

it('shows the add inmate page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('inmate-registry.inmates.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('inmate-registry/inmates/create')
            ->has('genders', 2)
        );
});

it('shows inmate detail page', function (): void {
    $user = User::factory()->create();
    $inmate = Inmate::factory()->create();

    $this->actingAs($user)
        ->get(route('inmate-registry.inmates.show', $inmate))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('inmate-registry/inmates/show')
            ->where('inmate.id', (string) $inmate->id)
        );
});

it('shows inmate edit page', function (): void {
    $user = User::factory()->create();
    $inmate = Inmate::factory()->create();

    $this->actingAs($user)
        ->get(route('inmate-registry.inmates.edit', $inmate))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('inmate-registry/inmates/edit')
            ->where('inmate.id', (string) $inmate->id)
            ->has('genders', 2)
        );
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
        ->post(route('inmate-registry.inmates.store'), $payload)
        ->assertRedirectContains('/inmate-registry/inmates/');

    $this->assertDatabaseHas('inmates', [
        'inmate_number' => 'N-2026-0001',
        'full_name' => 'Budi Santoso',
        'gender' => InmateGender::Male->value,
    ]);
});

it('rejects invalid inmate payloads on create', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('inmate-registry.inmates.store'), [
            'full_name' => '',
            'inmate_number' => 'bad number',
            'gender' => 'unknown',
            'birth_date' => now()->addDay()->toDateString(),
        ])
        ->assertSessionHasErrors(['full_name', 'inmate_number', 'gender', 'birth_date']);
});

it('allows authenticated users to update inmates', function (): void {
    $user = User::factory()->create();
    $inmate = Inmate::factory()->create([
        'full_name' => 'Old Name',
        'inmate_number' => 'N-2026-0100',
        'gender' => InmateGender::Male->value,
    ]);

    $this->actingAs($user)
        ->patch(route('inmate-registry.inmates.update', $inmate), [
            'full_name' => 'Updated Name',
            'inmate_number' => 'N-2026-0100',
            'gender' => InmateGender::Female->value,
            'birth_date' => '1991-01-01',
            'nationality' => 'Indonesia',
        ])
        ->assertRedirect(route('inmate-registry.inmates.show', $inmate));

    $this->assertDatabaseHas('inmates', [
        'id' => $inmate->id,
        'full_name' => 'Updated Name',
        'gender' => InmateGender::Female->value,
    ]);
});

it('allows authenticated users to delete inmates', function (): void {
    $user = User::factory()->create();
    $inmate = Inmate::factory()->create();

    $this->actingAs($user)
        ->delete(route('inmate-registry.inmates.destroy', $inmate))
        ->assertRedirect(route('inmate-registry.inmates.index'));

    $this->assertSoftDeleted('inmates', [
        'id' => $inmate->id,
    ]);
});

it('blocks guests from inmate endpoints', function (): void {
    $this->get(route('inmate-registry.inmates.index'))
        ->assertRedirect(route('login'));

    $this->get(route('inmate-registry.inmates.create'))
        ->assertRedirect(route('login'));
});
