<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    	if(substr($value, 0, 2) == '62') {
    		$fail("The :attribute can't contains 62");
    	} else {
    		if(substr($value, 0, 1) != '8') {
	    		$fail("The :attribute can't contains 08");
	    	}
    	}
    }
}
