<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidClientPlatformHeaderException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $client = $request->header('X-Client');

        if (!in_array($client, config('app.trusted_client_platforms'))){
            throw new InvalidClientPlatformHeaderException();
        }

        $request->attributes->set('client',$client);


        return $next($request);
    }
}
