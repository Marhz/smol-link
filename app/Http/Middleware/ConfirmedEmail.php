<?php

namespace App\Http\Middleware;

use Closure;

class ConfirmedEmail
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
        if (! $request->user()->isConfirmed()) {
            $message = "You must confirm your email";
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 403);
            }
            return redirect('/')->with('flash', $message);
        }
        return $next($request);
    }
}
