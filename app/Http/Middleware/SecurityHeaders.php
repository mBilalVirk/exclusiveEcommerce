<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ✅ Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // ✅ Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ✅ Basic XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}