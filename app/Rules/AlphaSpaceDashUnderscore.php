<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlphaSpaceDashUnderscore implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Regular expression to allow letters, spaces, dashes, and underscores
        if(!preg_match('/^[A-Za-z\s\-_]+$/', $value) ) {
            $fail('The :attribute must be letters, spaces, dashes, and underscores.');
        };
    }

}
