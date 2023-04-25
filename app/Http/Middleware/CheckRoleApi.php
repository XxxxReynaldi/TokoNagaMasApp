<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWT;

class CheckRoleApi
{


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {

        try {
            $user = JWTAuth::parseToken()->authenticate();
            $role = strtolower($user->role->name);
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ResponseFormatter::error(['message' => 'Token is Invalid'], 'Authentication Failed', 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ResponseFormatter::error(['message' => 'Token is Expired'], 'Authentication Failed', 401);
            } else {
                return ResponseFormatter::error(['message' => 'Authorization Token not found'], 'Authentication Failed', 401);
            }
            // return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check user role
        if (!$user || !in_array($role, $roles)) {
            return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authorization Failed', 403);
        }

        return $next($request);
    }
}
