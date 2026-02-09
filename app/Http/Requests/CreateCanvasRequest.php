<?php

namespace App\Http\Requests;

use App\Enums\CanvaAccess;
use App\Enums\CanvaCategory;
use App\Enums\CanvaVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCanvasRequest extends FormRequest
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
            'name' => 'required',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'colors' => 'required',
            'access' => ['required', Rule::enum(CanvaAccess::class)],
            'visibility' => ['required', Rule::enum(CanvaVisibility::class)],
            'category' => ['exclude_if:visibility,private', 'required', Rule::enum(CanvaCategory::class)],
        ];
    }
}
