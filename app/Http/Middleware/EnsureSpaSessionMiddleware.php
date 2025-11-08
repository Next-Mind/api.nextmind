<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Symfony\Component\HttpFoundation\Response;

class EnsureSpaSessionMiddleware
{
    public function __construct(private StartSession $startSession)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->attributes->get('client') !== 'spa') {
            return $next($request);
        }

        return $this->startSession->handle($request, $next);
    }
}
