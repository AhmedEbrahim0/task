<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ForceLoggedOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request,Closure $next): Response
    {

        if($request->has('token')){
            $canAccess = false;

            $apiToken=$request->token;
            if ($apiToken) {
                foreach(User::all() as $user) {
                    if($user->token1 == $apiToken || $user->token2 == $apiToken ) {
                        $canAccess = true;
                    }
                }
            }

            if($canAccess == false) {
                return response()->json([
                    'message' => "You're logged in from two devices",
                ], 422);
                // abort(403, 'Access denied');
            }
        }
        return $next($request);
    }
}