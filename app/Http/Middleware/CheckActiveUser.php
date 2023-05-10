<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth,Session;
class CheckActiveUser
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
        if(auth()->check() && auth()->user()->status <> 'active'){
            Auth::logout();
            Session::flash('warning','Your Account is not active, please contact Admin.');
            return redirect()->route('login');
        }
        return $next($request);
    }
}
