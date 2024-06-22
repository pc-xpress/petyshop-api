<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CheckPasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * This method checks if the given value matches the user's current password.
     * If the value does not match, it calls the $fail callback with an error message.
     *
     * @param  string  $attribute  The name of the validated attribute.
     * @param  mixed  $value  The value of the validated attribute.
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail  The callback to call if the validation fails.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the given value matches the user's current password.
        // If the value does not match, call the $fail callback with an error message.
        Hash::check($value, auth()->user()->password)
            ?: $fail('The password does not match.');
    }
}
