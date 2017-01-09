<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token_timeout = 60*60*3;
        $user = User::where('api_token', $request->header('api_token'))->first();
        if (!$user){
            abort(403);
        }
        if (time() - strtotime($user->api_created) > $token_timeout){
            abort(403);
        }
        $user->bumpKey();
        $user->save();
        return $next($request);
    }
}
