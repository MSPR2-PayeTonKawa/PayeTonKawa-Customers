<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyInternalApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $provided = $request->header('X-Internal-Api-Key')
            ?: (preg_match('/^ApiKey\s+(.+)$/i', $request->header('Authorization', ''), $m) ? $m[1] : null);

        $expected = env('INTERNAL_API_KEY');

        if (!$expected || !$provided || !hash_equals($expected, $provided)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
