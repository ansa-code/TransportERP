<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RejectMisspelledEmailDomain implements ValidationRule
{
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if (!is_string($value) || trim($value) === '') {
            return;
        }

        $email = strtolower(trim($value));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $domain = substr(strrchr($email, '@'), 1);

        $blockedDomains = [
            'gamil.com',
            'gmal.com',
            'gmall.com',
            'gmaall.com',
            'gml.com',
            'gail.com',
            'gmial.com',
            'gmai.com',
            'gmail.co',
            'gmail.con',
            'gmaili.com',
            'gmaill.com',
            'gmaill.co',
            'gmaik.com',
            'gmaol.com',
            'gmaul.com',
            'gmeil.com',
            'gmaill.net',
            'gmaiil.com',
        ];

        if (in_array($domain, $blockedDomains, true)) {
            $fail('Please check the spelling of your email domain.');
        }
    }
}