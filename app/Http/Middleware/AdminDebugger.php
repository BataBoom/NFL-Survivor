<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminDebugger
{
    public function handle($request, Closure $next)
    {

            if (auth()->id() === 1) {
            config(['app.debug' => true]);
            }

            return $next($request);
    }
}
