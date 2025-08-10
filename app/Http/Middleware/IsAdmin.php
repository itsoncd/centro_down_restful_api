<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use App\Helpers\ApiResponse; // opcional, si quieres usar ApiResponse

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    /** @var \App\Models\User|null $user */
    $user = \Illuminate\Support\Facades\Auth::guard('api')->user() ?? \Illuminate\Support\Facades\Auth::user();

    if (!$user) {
        return ApiResponse::error('Unauthenticated.', 401);
    }

    $isAdmin = false;

    if (method_exists($user, 'roles')) {
        $isAdmin = $user->roles()->where('name', 'admin')->exists();
    } else {
        $isAdmin = isset($user->role) && $user->role === 'admin';
    }

    if ($isAdmin) {
        return $next($request);
    }

    return ApiResponse::error('You are not an ADMIN', 403);
}

}
