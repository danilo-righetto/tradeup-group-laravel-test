<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cep implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cep = preg_replace('/[^0-9]/', '', $value); // Remove os caracteres especiais

        if (strlen($cep) !== 8) {
            $fail('O :attribute não é um CEP válido.');
        }
    }
}
