<?php

declare(strict_types=1);

namespace App\Modules\InmateRegistry\Interfaces\Http\Requests;

use App\Modules\InmateRegistry\Domain\Enums\InmateGender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInmateRequest extends FormRequest
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
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'inmate_number' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:inmates,inmate_number'],
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
