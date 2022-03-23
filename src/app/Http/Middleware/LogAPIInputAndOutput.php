<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAPIInputAndOutput
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
        $rd = json_decode($request->getContent()); // Request Data = rd
        Log::debug("REQUEST BODY " . $request->url() . ': ' . $request->getContent());
        $response = $next($request);
        Log::debug("RESPONSE BODY " . $request->url() . ': ' . $response->getContent());
        return $response;
    }
}
