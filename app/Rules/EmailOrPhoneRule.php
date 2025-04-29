<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrPhoneRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {}

    public function passes($attribute, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return (new PhoneRule())->passes($attribute, $value);
    }

    public function message()
    {
        return 'The :attribute must be a valid email address or phone number.';
    }
}