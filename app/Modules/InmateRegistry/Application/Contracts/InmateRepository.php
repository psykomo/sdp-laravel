<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Application\Contracts;

use App\Modules\InmateRegistry\Application\DataTransferObjects\CreateInmateData;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface InmateRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function create(CreateInmateData $data): Inmate;
}
