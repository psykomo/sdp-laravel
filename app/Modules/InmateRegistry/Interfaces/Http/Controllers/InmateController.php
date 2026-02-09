<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Interfaces\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\InmateRegistry\Application\Contracts\InmateRepository;
use App\Modules\InmateRegistry\Application\DataTransferObjects\CreateInmateData;
use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use App\Modules\InmateRegistry\Interfaces\Http\Requests\StoreInmateRequest;
use App\Modules\InmateRegistry\Interfaces\Http\Requests\UpdateInmateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InmateController extends Controller
{
    public function index(Request $request, InmateRepository $inmates): Response
    {
        $perPage = $request->integer('per_page', 15);
        $safePerPage = max(1, min($perPage, 100));

        $paginatedInmates = $inmates->paginate($safePerPage);

        return Inertia::render('inmate-registry/inmates/index', [
            'inmates' => [
                'data' => array_map($this->serializeInmate(...), $paginatedInmates->items()),
                'meta' => [
                    'current_page' => $paginatedInmates->currentPage(),
                    'per_page' => $paginatedInmates->perPage(),
                    'total' => $paginatedInmates->total(),
                    'last_page' => $paginatedInmates->lastPage(),
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('inmate-registry/inmates/create', [
            'genders' => $this->genderOptions(),
        ]);
    }

    public function show(Inmate $inmate): Response
    {
        return Inertia::render('inmate-registry/inmates/show', [
            'inmate' => $this->serializeInmate($inmate),
        ]);
    }

    public function edit(Inmate $inmate): Response
    {
        return Inertia::render('inmate-registry/inmates/edit', [
            'inmate' => $this->serializeInmate($inmate),
            'genders' => $this->genderOptions(),
        ]);
    }

    public function store(StoreInmateRequest $request, InmateRepository $inmates): RedirectResponse
    {
        $inmate = $inmates->create(
            CreateInmateData::fromValidatedData($request->validated())
        );

        return to_route('inmate-registry.inmates.show', $inmate);
    }

    public function update(UpdateInmateRequest $request, Inmate $inmate, InmateRepository $inmates): RedirectResponse
    {
        $updatedInmate = $inmates->update(
            $inmate,
            CreateInmateData::fromValidatedData($request->validated())
        );

        return to_route('inmate-registry.inmates.show', $updatedInmate);
    }

    public function destroy(Inmate $inmate, InmateRepository $inmates): RedirectResponse
    {
        $inmates->delete($inmate);

        return to_route('inmate-registry.inmates.index');
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    private function genderOptions(): array
    {
        return array_map(
            static fn (InmateGender $gender): array => [
                'value' => $gender->value,
                'label' => str($gender->value)->title()->value(),
            ],
            InmateGender::cases(),
        );
    }

    /**
     * @return array{
     *     id: string,
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
            'id' => (string) $inmate->id,
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
