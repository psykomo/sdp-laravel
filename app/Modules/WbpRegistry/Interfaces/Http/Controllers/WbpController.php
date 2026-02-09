<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\WbpRegistry\Application\Contracts\WbpRepository;
use App\Modules\WbpRegistry\Application\DataTransferObjects\CreateWbpData;
use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use App\Modules\WbpRegistry\Interfaces\Http\Requests\StoreWbpRequest;
use App\Modules\WbpRegistry\Interfaces\Http\Requests\UpdateWbpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WbpController extends Controller
{
    public function index(Request $request, WbpRepository $wbpRepository): Response
    {
        $perPage = $request->integer('per_page', 15);
        $safePerPage = max(1, min($perPage, 100));

        $paginatedWbp = $wbpRepository->paginate($safePerPage);

        return Inertia::render('wbp-registry/wbp/index', [
            'wbp' => [
                'data' => array_map($this->serializeWbp(...), $paginatedWbp->items()),
                'meta' => [
                    'current_page' => $paginatedWbp->currentPage(),
                    'per_page' => $paginatedWbp->perPage(),
                    'total' => $paginatedWbp->total(),
                    'last_page' => $paginatedWbp->lastPage(),
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('wbp-registry/wbp/create', [
            'genders' => $this->genderOptions(),
        ]);
    }

    public function show(Wbp $wbp): Response
    {
        return Inertia::render('wbp-registry/wbp/show', [
            'wbp' => $this->serializeWbp($wbp),
        ]);
    }

    public function edit(Wbp $wbp): Response
    {
        return Inertia::render('wbp-registry/wbp/edit', [
            'wbp' => $this->serializeWbp($wbp),
            'genders' => $this->genderOptions(),
        ]);
    }

    public function store(StoreWbpRequest $request, WbpRepository $wbpRepository): RedirectResponse
    {
        $wbp = $wbpRepository->create(
            CreateWbpData::fromValidatedData($request->validated())
        );

        return to_route('wbp-registry.wbp.show', $wbp);
    }

    public function update(UpdateWbpRequest $request, Wbp $wbp, WbpRepository $wbpRepository): RedirectResponse
    {
        $updatedWbp = $wbpRepository->update(
            $wbp,
            CreateWbpData::fromValidatedData($request->validated())
        );

        return to_route('wbp-registry.wbp.show', $updatedWbp);
    }

    public function destroy(Wbp $wbp, WbpRepository $wbpRepository): RedirectResponse
    {
        $wbpRepository->delete($wbp);

        return to_route('wbp-registry.wbp.index');
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function genderOptions(): array
    {
        return array_map(
            static fn (WbpGender $gender): array => [
                'value' => $gender->value,
                'label' => str($gender->value)->title()->value(),
            ],
            WbpGender::cases(),
        );
    }

    /**
     * @return array{
     *     id: string,
     *     public_id: string,
     *     full_name: string,
     *     wbp_number: string,
     *     gender: string,
     *     birth_date: string|null,
     *     nationality: string|null,
     *     created_at: string|null
     * }
     */
    private function serializeWbp(Wbp $wbp): array
    {
        return [
            'id' => (string) $wbp->id,
            'public_id' => (string) $wbp->public_id,
            'full_name' => (string) $wbp->full_name,
            'wbp_number' => (string) $wbp->wbp_number,
            'gender' => $wbp->gender->value,
            'birth_date' => $wbp->birth_date?->toDateString(),
            'nationality' => $wbp->nationality,
            'created_at' => $wbp->created_at?->toISOString(),
        ];
    }
}
