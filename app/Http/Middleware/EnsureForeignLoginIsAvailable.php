<?php

namespace App\Http\Middleware;

use App\Support\ForeignLoginAccess;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class EnsureForeignLoginIsAvailable
{
    public function __construct(private ForeignLoginAccess $foreignLoginAccess) {}

    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (! $this->foreignLoginAccess->allows($request)) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
