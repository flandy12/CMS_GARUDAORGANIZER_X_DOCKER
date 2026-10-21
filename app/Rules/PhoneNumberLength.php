<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberLength implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(strlen($value) > 15) {
        	$fail("The length of :attribute can't more than 15 characters");
        }

        if(strlen($value) < 9) {
        	$fail("The length of :attribute can't less than 9 characters");
        }
    }
}
