<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Application\DataTransferObjects;

use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use Carbon\CarbonImmutable;

readonly class CreateWbpData
{
    public function __construct(
        public string $fullName,
        public string $wbpNumber,
        public WbpGender $gender,
        public ?CarbonImmutable $birthDate,
        public ?string $nationality,
    ) {}

    /**
     * @param array{
     *     full_name: string,
     *     wbp_number: string,
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
            wbpNumber: $validatedData['wbp_number'],
            gender: WbpGender::from($validatedData['gender']),
            birthDate: $birthDate,
            nationality: $validatedData['nationality'] ?? null,
        );
    }
}
