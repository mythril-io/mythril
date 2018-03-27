<?php

namespace App\Http\Middleware;

use Closure;

class AddAuthHeader
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
        $access_token = $request->cookie('access_token');
        if ( ! $access_token) {
            return $next($request);
        }

        $request->headers->set('Authorization', "Bearer {$access_token}");

        return $next($request);
    }
}
