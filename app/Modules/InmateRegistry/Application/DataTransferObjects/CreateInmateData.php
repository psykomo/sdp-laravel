<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Application\DataTransferObjects;

use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use Carbon\CarbonImmutable;

readonly class CreateInmateData
{
    public function __construct(
        public string $fullName,
        public string $inmateNumber,
        public InmateGender $gender,
        public ?CarbonImmutable $birthDate,
        public ?string $nationality,
    ) {}

    /**
     * @param array{
     *     full_name: string,
     *     inmate_number: string,
     *     gender: string,
     *     birth_date?: string|null,
     *     nationality?: string|null
     * } $validatedData
     */
    public static function fromValidatedData(array $validatedData): self
    {
        $birthDate = null;

        if (! empty($validatedData['birth_date'])) {
            $birthDate = CarbonImmutable::parse($validatedData['birth_date']);
        }

        return new self(
            fullName: $validatedData['full_name'],
            inmateNumber: $validatedData['inmate_number'],
            gender: InmateGender::from($validatedData['gender']),
            birthDate: $birthDate,
            nationality: $validatedData['nationality'] ?? null,
        );
    }
}
