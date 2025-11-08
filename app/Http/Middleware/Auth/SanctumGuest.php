<?php

namespace App\Http\Middleware\Auth;

use App\Constants\HttpCode;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanctumGuest
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check()) {
            return $this->fail(
                message: 'Only guest can access this resource',
                status: 401,
                code: HttpCode::ONLY_GUEST_ACCESS
            );
        }

        return $next($request);
    }
}
