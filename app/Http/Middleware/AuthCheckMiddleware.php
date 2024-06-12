<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AuthCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Unauthorized token'], 401);
        }

        $token = substr($authorizationHeader, 7); // Remove "Bearer " prefix

        $user = User::where('api_token', $token)
                    ->where('token_expires_at', '>', now())
                    ->first();


        if (!$user) {
            return response()->json(['error' => 'Unauthorized user not'], 401);
        }else{
            echo "token is ok";
        }

        // Attach authenticated user to the request
        $request->merge(['user' => $user]);

        return $next($request);
    }
}
