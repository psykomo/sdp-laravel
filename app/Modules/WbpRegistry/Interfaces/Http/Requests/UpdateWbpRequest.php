<?php

declare(strict_types=1);

namespace App\Modules\WbpRegistry\Interfaces\Http\Requests;

use App\Modules\WbpRegistry\Domain\Enums\WbpGender;
use App\Modules\WbpRegistry\Infrastructure\Models\Wbp;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWbpRequest extends FormRequest
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
        /** @var Wbp|null $wbp */
        $wbp = $this->route('wbp');

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'wbp_number' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('wbp', 'wbp_number')->ignore($wbp?->id),
            ],
            'gender' => ['required', Rule::enum(WbpGender::class)],
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
            'wbp_number.alpha_dash' => 'WBP number may only contain letters, numbers, dashes, and underscores.',
            'birth_date.before_or_equal' => 'Birth date must not be in the future.',
        ];
    }
}
