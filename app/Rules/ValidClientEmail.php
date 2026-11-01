<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidClientEmail implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if ($value === null || trim((string) $value) === '') {
            return;
        }

        $email = strtolower(trim((string) $value));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid Gmail address.');
            return;
        }

        if (!preg_match('/^[A-Z0-9._%+-]+@gmail\.com$/i', $email)) {
            $fail('Only Gmail addresses ending in @gmail.com are allowed.');
        }
    }
}