<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Application\Contracts;

use App\Modules\WbpRegistry\Application\DataTransferObjects\CreateWbpData;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WbpRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function create(CreateWbpData $data): Wbp;

    public function update(Wbp $wbp, CreateWbpData $data): Wbp;

    public function delete(Wbp $wbp): void;
}
