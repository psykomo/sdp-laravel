<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Infrastructure\Eloquent;

use App\Modules\InmateRegistry\Application\Contracts\InmateRepository;
use App\Modules\InmateRegistry\Application\DataTransferObjects\CreateInmateData;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class EloquentInmateRepository implements InmateRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Inmate::query()
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(CreateInmateData $data): Inmate
    {
        return Inmate::query()->create([
            'public_id' => (string) Str::ulid(),
            'full_name' => $data->fullName,
            'inmate_number' => $data->inmateNumber,
            'gender' => $data->gender,
            'birth_date' => $data->birthDate,
            'nationality' => $data->nationality,
        ]);
    }
}
