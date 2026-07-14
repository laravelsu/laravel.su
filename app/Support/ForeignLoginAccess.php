<?php

namespace App\Support;

use App\Services\IpInfo;
use Illuminate\Http\Request;

final readonly class ForeignLoginAccess
{
    public function __construct(private IpInfo $ipInfo) {}

    public function allows(Request $request): bool
    {
        $countryCode = $this->ipInfo->countryCodeFor($request->ip());

        return filled($countryCode) && $countryCode !== 'RU';
    }
}
