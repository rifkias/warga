<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiLogController as ApiLog;
class GiveMenutoSession
{

    public function __construct()
    {
        $this->ApiLog = new ApiLog;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()){
            $updated = $this->ApiLog->getLastesUpdateMenu();
            if(!$request->session()->has('userMenu')){
               $this->addSession($request,$updated);
            }else{
                if($request->session()->has('userMenuChange')){
                    if($request->session()->get('userMenuChange') <> $updated){
                        $this->addSession($request,$updated);
                    }
                }else{
                    $this->addSession($request,$updated);
                }
            }
        }
        return $next($request);
    }
    public function addSession($request,$updated)
    {
        $menu = $this->ApiLog->getMenu();
        $request->session()->put('userMenu',$menu);
        $request->session()->put('userMenuChange',$updated);
    }
}
