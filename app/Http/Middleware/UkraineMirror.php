<?php

namespace App\Http\Middleware;

use App\Services\Mirror;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UkraineMirror
{
    public function __construct(protected Mirror $mirror) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->mirror->hasMirror()) {
            config()->set('cache.default', 'null');
        }

        return $next($request);
    }
}
