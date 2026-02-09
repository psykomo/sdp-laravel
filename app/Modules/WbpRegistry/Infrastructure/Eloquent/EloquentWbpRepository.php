<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Infrastructure\Eloquent;

use App\Modules\WbpRegistry\Application\Contracts\WbpRepository;
use App\Modules\WbpRegistry\Application\DataTransferObjects\CreateWbpData;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class EloquentWbpRepository implements WbpRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Wbp::query()
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function create(CreateWbpData $data): Wbp
    {
        return Wbp::query()->create([
            'public_id' => (string) Str::ulid(),
            'full_name' => $data->fullName,
            'wbp_number' => $data->wbpNumber,
            'gender' => $data->gender,
            'birth_date' => $data->birthDate,
            'nationality' => $data->nationality,
        ]);
    }

    public function update(Wbp $wbp, CreateWbpData $data): Wbp
    {
        $wbp->fill([
            'full_name' => $data->fullName,
            'wbp_number' => $data->wbpNumber,
            'gender' => $data->gender,
            'birth_date' => $data->birthDate,
            'nationality' => $data->nationality,
        ]);

        $wbp->save();

        return $wbp->refresh();
    }

    public function delete(Wbp $wbp): void
    {
        $wbp->delete();
    }
}
