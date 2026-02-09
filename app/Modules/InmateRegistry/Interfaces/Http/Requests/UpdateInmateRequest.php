<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Interfaces\Http\Requests;

use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use App\Modules\InmateRegistry\Infrastructure\Models\Inmate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInmateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|\Illuminate\Contracts\Validation\ValidationRule>>
     */
    public function rules(): array
    {
        /** @var Inmate|null $inmate */
        $inmate = $this->route('inmate');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'inmate_number' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('inmates', 'inmate_number')->ignore($inmate?->id),
            ],
            'gender' => ['required', Rule::enum(InmateGender::class)],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'nationality' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'inmate_number.alpha_dash' => 'Inmate number may only contain letters, numbers, dashes, and underscores.',
            'birth_date.before_or_equal' => 'Birth date must not be in the future.',
        ];
    }
}
