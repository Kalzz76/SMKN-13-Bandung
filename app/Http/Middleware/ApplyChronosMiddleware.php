<?php

namespace App\Http\Middleware;

use App\Services\ChronosService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyChronosMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        ChronosService::apply();
        view()->share('chronos', ChronosService::status());

        return $next($request);
    }
}
