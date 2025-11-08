<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidClientPlatformHeaderException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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

        $this->assertClientRouteCompatibility($request, $client);

        $shouldForgetCookie = false;

        if ($this->isStatelessClient($client)) {
            $shouldForgetCookie = $this->purgeSessionCookieFromRequest($request);
        }

        $request->attributes->set('client',$client);

        $response = $next($request);

        if ($this->isStatelessClient($client) && $shouldForgetCookie) {
            $this->forgetSessionCookie($response);
        }

        return $response;
    }

    private function assertClientRouteCompatibility(Request $request, string $client): void
    {
        if ($request->is('login') && $client === 'spa') {
            throw new InvalidClientPlatformHeaderException();
        }

        if ($request->is('login/web') && $client !== 'spa') {
            throw new InvalidClientPlatformHeaderException();
        }
    }

    private function isStatelessClient(string $client): bool
    {
        return $client !== 'spa';
    }

    private function purgeSessionCookieFromRequest(Request $request): bool
    {
        $cookieName = config('session.cookie');

        if ($cookieName && $request->cookies->has($cookieName)) {
            $request->cookies->remove($cookieName);
            return true;
        }

        return false;
    }

    private function forgetSessionCookie(Response $response): void
    {
        $cookieName = config('session.cookie');

        if (!$cookieName) {
            return;
        }

        $forgetCookie = Cookie::forget($cookieName);

        $response->headers->setCookie($forgetCookie);
    }
}
