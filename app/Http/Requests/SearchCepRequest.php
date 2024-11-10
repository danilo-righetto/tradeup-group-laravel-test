<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Cep;

class SearchCepRequest extends FormRequest
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
            'cep' => ['required', new Cep, 'max:9']
        ];
    }

    public function messages(): array
    {
        return [
            'cep.required' => 'O campo cep é obrigatório.',
            'cep.max' => 'O campo cep não pode ter mais que 9 caracteres.'
        ];
    }
}
