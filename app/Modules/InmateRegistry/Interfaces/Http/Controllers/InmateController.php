<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InmateRegistry\Application\Contracts\InmateRepository;
use App\Modules\InmateRegistry\Application\DataTransferObjects\CreateInmateData;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use App\Modules\InmateRegistry\Interfaces\Http\Requests\StoreInmateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InmateController extends Controller
{
    public function index(Request $request, InmateRepository $inmates): JsonResponse
    {
        $perPage = $request->integer('per_page', 15);
        $safePerPage = max(1, min($perPage, 100));

        $paginatedInmates = $inmates->paginate($safePerPage);

        return response()->json([
            'data' => array_map($this->serializeInmate(...), $paginatedInmates->items()),
            'meta' => [
                'current_page' => $paginatedInmates->currentPage(),
                'per_page' => $paginatedInmates->perPage(),
                'total' => $paginatedInmates->total(),
            ],
        ]);
    }

    public function store(StoreInmateRequest $request, InmateRepository $inmates): JsonResponse
    {
        $inmate = $inmates->create(
            CreateInmateData::fromValidatedData($request->validated())
        );

        return response()->json([
            'data' => $this->serializeInmate($inmate),
        ], 201);
    }

    /**
     * @return array{
     *     public_id: string,
     *     full_name: string,
     *     inmate_number: string,
     *     gender: string,
     *     birth_date: string|null,
     *     nationality: string|null,
     *     created_at: string|null
     * }
     */
    private function serializeInmate(Inmate $inmate): array
    {
        return [
            'public_id' => (string) $inmate->public_id,
            'full_name' => (string) $inmate->full_name,
            'inmate_number' => (string) $inmate->inmate_number,
            'gender' => $inmate->gender->value,
            'birth_date' => $inmate->birth_date?->toDateString(),
            'nationality' => $inmate->nationality,
            'created_at' => $inmate->created_at?->toISOString(),
        ];
    }
}
