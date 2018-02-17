<?php

namespace App\Http\Middleware;

use Closure;

class PrivateUrlCheckMiddleware
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
        $url = $request->route('url');
        if($url->isPrivate()) {
            if (auth()->guest() || $url->user_id !== auth()->id()){
                abort(403);
            }
        }
        return $next($request);
    }
}
