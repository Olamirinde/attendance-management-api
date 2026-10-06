<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use App\Models\AdminModel;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token missing'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        } catch (ExpiredException $e) {
            return response()->json(['message' => 'Token expired'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        $admin = AdminModel::find($decoded->sub);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 401);
        }

        $request->attributes->set('admin', $admin);

        return $next($request);
    }
}