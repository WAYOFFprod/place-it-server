<?php

namespace App\Http\Requests;

use App\Enums\CanvasRequestType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCanvasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'scope' => ['required', Rule::enum(CanvasRequestType::class)],
            'sort' => [Rule::in(['asc', 'desc'])],
            'favorit' => 'boolean',
            'search' => 'string',
        ];
    }
}
