<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OptimizeResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // إضافة headers للتحسين
        $response->headers->set('Cache-Control', 'public, max-age=31536000'); // سنة واحدة للصور
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // ضغط الـ response إذا أمكن
        if ($request->header('Accept-Encoding') && strpos($request->header('Accept-Encoding'), 'gzip') !== false) {
            $response->headers->set('Content-Encoding', 'gzip');
        }

        return $response;
    }
}