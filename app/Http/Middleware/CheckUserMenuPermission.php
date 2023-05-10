<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiLogController as ApiLog;
use Session;
class CheckUserMenuPermission
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
        $ApiLog = New ApiLog;
        if($ApiLog->checkUserMenuPermission()){
            return $next($request);
        }else{
            Session::flash('warning',"You Doesn't have Access to this route, Please Contact Administrator");
            return redirect('/');
        }
    }
}
