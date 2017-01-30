<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Redirect;
use Request;

class Logged {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(Auth::check()) {
            return Redirect::intended ( '/user');
        }
        return $next($request);
    }
}
