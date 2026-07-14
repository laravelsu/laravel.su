<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class IpInfo
{
    public function countryCodeFor(string $ip): ?string
    {
        if (blank($token = config('services.ipinfo.token'))) {
            return null;
        }

        $countryCode = rescue(
            fn () => Http::baseUrl(config('services.ipinfo.url'))
                ->acceptJson()
                ->timeout(config('services.ipinfo.timeout'))
                ->get(rawurlencode($ip), ['token' => $token])
                ->throw()
                ->json('country_code'),
            report: false,
        );

        return is_string($countryCode) && filled($countryCode)
            ? Str::of($countryCode)->trim()->upper()->toString()
            : null;
    }
}
