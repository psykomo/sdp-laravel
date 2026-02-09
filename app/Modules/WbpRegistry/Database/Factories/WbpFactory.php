<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Database\Factories;

use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Wbp>
 */
class WbpFactory extends Factory
{
    protected $model = Wbp::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'public_id' => (string) Str::ulid(),
            'full_name' => fake()->name(),
            'wbp_number' => sprintf('WBP-%08d', fake()->unique()->numberBetween(1, 99_999_999)),
            'gender' => fake()->randomElement(WbpGender::cases())->value,
            'birth_date' => fake()->dateTimeBetween('-70 years', '-18 years')->format('Y-m-d'),
            'nationality' => 'Indonesia',
        ];
    }
}
