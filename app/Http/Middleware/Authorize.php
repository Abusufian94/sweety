<?php

namespace App\Http\Middleware;

use Closure;

class Authorize
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
        if($request->headers->has('Authorization'))
        {
            $request->headers->set('Accept', 'application/json');
            return $next($request);
        }
        return response()->json(['stat'=>false,"message"=>"Unauthorized"],401);

    }
}
