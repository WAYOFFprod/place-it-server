<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info("----- REQUEST -----");
        // Log::info($request);

        Log::info($request->host());
        // Log::info($request->httpHost());
        Log::info($request->cookie('laravel_session'));
        // Log::info($request->cookie('XSRF-TOKEN'));


        $response = $next($request);
        // Log::info($response->cookie('laravel_session'));
        // Perform action
        return $response;
    }
}
