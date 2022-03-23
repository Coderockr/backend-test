<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FilterNonAuthenticatedAPIRequests
{

    protected $currentUser;
 
    public function __construct(\App\Services\CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
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
        $rd = json_decode($request->getContent()); 
        if($this->currentUser->RestoreSessionByToken($rd->_credentials->token)) {
            return $next($request);
        }else{
            return response()->json(["status" => 4], 401);
        }
        //codigo 4 = token invalido
    }
}
