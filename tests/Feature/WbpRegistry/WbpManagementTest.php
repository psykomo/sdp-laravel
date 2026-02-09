<?php

declare(strict_types=1);

use App\Models\User;
use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Inertia\Testing\AssertableInertia as Assert;

it('shows the wbp list page to authenticated users', function (): void {
    $user = User::factory()->create();
    Wbp::factory()->count(2)->create();

    $this->actingAs($user)
        ->get(route('wbp-registry.wbp.index'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('wbp-registry/wbp/index')
            ->has('wbp.data', 2)
            ->has('wbp.meta')
            ->where('wbp.meta.last_page', 1)
        );
});

it('shows the add wbp page', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('wbp-registry.wbp.create'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('wbp-registry/wbp/create')
            ->has('genders', 2)
        );
});

it('shows wbp detail page', function (): void {
    $user = User::factory()->create();
    $wbp = Wbp::factory()->create();

    $this->actingAs($user)
        ->get(route('wbp-registry.wbp.show', $wbp))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('wbp-registry/wbp/show')
            ->where('wbp.id', (string) $wbp->id)
        );
});

it('shows wbp edit page', function (): void {
    $user = User::factory()->create();
    $wbp = Wbp::factory()->create();

    $this->actingAs($user)
        ->get(route('wbp-registry.wbp.edit', $wbp))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('wbp-registry/wbp/edit')
            ->where('wbp.id', (string) $wbp->id)
            ->has('genders', 2)
        );
});

it('allows authenticated users to create wbp', function (): void {
    $user = User::factory()->create();

    $payload = [
        'full_name' => 'Budi Santoso',
        'wbp_number' => 'WBP-2026-0001',
        'gender' => WbpGender::Male->value,
        'birth_date' => '1990-02-10',
        'nationality' => 'Indonesia',
    ];

    $this->actingAs($user)
        ->post(route('wbp-registry.wbp.store'), $payload)
        ->assertRedirectContains('/wbp-registry/wbp/');

    $this->assertDatabaseHas('wbp', [
        'wbp_number' => 'WBP-2026-0001',
        'full_name' => 'Budi Santoso',
        'gender' => WbpGender::Male->value,
    ]);
});

it('rejects invalid wbp payloads on create', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('wbp-registry.wbp.store'), [
            'full_name' => '',
            'wbp_number' => 'bad number',
            'gender' => 'unknown',
            'birth_date' => now()->addDay()->toDateString(),
        ])
        ->assertSessionHasErrors(['full_name', 'wbp_number', 'gender', 'birth_date']);
});

it('allows authenticated users to update wbp', function (): void {
    $user = User::factory()->create();
    $wbp = Wbp::factory()->create([
        'full_name' => 'Old Name',
        'wbp_number' => 'WBP-2026-0100',
        'gender' => WbpGender::Male->value,
    ]);

    $this->actingAs($user)
        ->patch(route('wbp-registry.wbp.update', $wbp), [
            'full_name' => 'Updated Name',
            'wbp_number' => 'WBP-2026-0100',
            'gender' => WbpGender::Female->value,
            'birth_date' => '1991-01-01',
            'nationality' => 'Indonesia',
        ])
        ->assertRedirect(route('wbp-registry.wbp.show', $wbp));

    $this->assertDatabaseHas('wbp', [
        'id' => $wbp->id,
        'full_name' => 'Updated Name',
        'gender' => WbpGender::Female->value,
    ]);
});

it('allows authenticated users to delete wbp', function (): void {
    $user = User::factory()->create();
    $wbp = Wbp::factory()->create();

    $this->actingAs($user)
        ->delete(route('wbp-registry.wbp.destroy', $wbp))
        ->assertRedirect(route('wbp-registry.wbp.index'));

    $this->assertSoftDeleted('wbp', [
        'id' => $wbp->id,
    ]);
});

it('blocks guests from wbp endpoints', function (): void {
    $this->get(route('wbp-registry.wbp.index'))
        ->assertRedirect(route('login'));

    $this->get(route('wbp-registry.wbp.create'))
        ->assertRedirect(route('login'));
});
