<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->user()) {
            return $response;
        }

        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => $request->method() . ' ' . ($request->route()?->getName() ?? $request->path()),
            'model_type' => null,
            'model_id' => null,
            'old_values' => null,
            'new_values' => null,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        return $response;
    }
}
