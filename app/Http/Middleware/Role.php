<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null, $type = null)
    {

        if ($type != null) {

            if ($type != auth($guard)->user()->role) {

                if ($request->wantsJson()) {

                    return response('Forbidden', 403);

                } 
                else {
                    
                    auth()->logout();
                    
                    return redirect('login')->with('error-notif', 'You\'re not an admin!');

                }

            }

        }


        return $next($request);
    }
}
