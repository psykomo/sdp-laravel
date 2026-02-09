<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Infrastructure\Models;

use App\Concerns\HasUuidV7;
use App\Modules\WbpRegistry\Database\Factories\WbpFactory;
use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wbp extends Model
{
    /** @use HasFactory<WbpFactory> */
    use HasFactory;

    use HasUuidV7;
    use SoftDeletes;

    protected $table = 'wbp';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'public_id',
        'full_name',
        'wbp_number',
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
            'gender' => WbpGender::class,
            'birth_date' => 'immutable_date',
        ];
    }

    protected static function newFactory(): Factory
    {
        return WbpFactory::new();
    }
}
