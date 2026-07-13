<?php

namespace App\Support;

use Illuminate\Http\Request;

final class ForeignLoginAccess
{
    private const BLOCKED_COUNTRIES = ['RU', 'T1', 'XX'];

    public function allows(Request $request): bool
    {
        $country = strtoupper(trim((string) $request->header('CF-IPCountry')));

        return preg_match('/^[A-Z]{2}$/', $country) === 1
            && ! in_array($country, self::BLOCKED_COUNTRIES, true);
    }
}
