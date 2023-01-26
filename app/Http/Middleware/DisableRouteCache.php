<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableRouteCache
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
        return $next($request)->withHeaders([
            "Cache-Control" => "no-store, no-cache, must-revalidate, max-age=0, private",
            "Expires" => "Tue, 03 Jul 2001 06:00:00 GMT",
            "Last-Modified" => gmdate("D, d M Y H:i:s") . " GMT",
            "Pragma" => "no-cache",
        ]);
    }
}
