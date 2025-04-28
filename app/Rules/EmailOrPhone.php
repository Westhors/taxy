<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailOrPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the value is a valid email
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return; // Valid email, validation passes
        }

        // Check if the value is a valid phone number (basic 10-digit number validation)
        // Adjust regex for your specific phone format
        if (!preg_match('/^[0-9]{12}$/', $value)) {
            $fail('The ' . $attribute . ' must be a valid email or phone number.');
        }
    }
}