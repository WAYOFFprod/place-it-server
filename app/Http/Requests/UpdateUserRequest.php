<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'field' => ['required', Rule::in(['email', 'name', 'discord_user', 'language'])],
            'value' => 'required',
        ];
    }

    public function withValidator($validator) {
        $validator->sometimes('value', 'required|email', function (Fluent $input) {
            return $input->field == 'email';
        });
        $validator->sometimes('value', [Rule::in(['fr','en'])], function (Fluent $input) {
            return $input->field == 'language';
        });

    }
}
