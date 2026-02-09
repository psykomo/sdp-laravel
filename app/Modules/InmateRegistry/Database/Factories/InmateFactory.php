<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Database\Factories;

use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Inmate>
 */
class InmateFactory extends Factory
{
    protected $model = Inmate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => (string) Str::ulid(),
            'full_name' => fake()->name(),
            'inmate_number' => sprintf('N-%08d', fake()->unique()->numberBetween(1, 99_999_999)),
            'gender' => fake()->randomElement(InmateGender::cases())->value,
            'birth_date' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'nationality' => 'Indonesia',
        ];
    }
}
