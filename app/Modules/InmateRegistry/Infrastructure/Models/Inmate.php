<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Infrastructure\Models;

use App\Concerns\HasUuidV7;
use App\Modules\InmateRegistry\Database\Factories\InmateFactory;
use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inmate extends Model
{
    /** @use HasFactory<InmateFactory> */
    use HasFactory;

    use HasUuidV7;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'full_name',
        'inmate_number',
        'gender',
        'birth_date',
        'nationality',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gender' => InmateGender::class,
            'birth_date' => 'immutable_date',
        ];
    }

    protected static function newFactory(): Factory
    {
        return InmateFactory::new();
    }
}
