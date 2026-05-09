<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Если пользователь залогинен И он админ — пропускаем
        if (auth()->check() && (auth()->user()->is_admin || auth()->user()->is_super_admin)) {
            return $next($request);
        }

        // Иначе — выкидываем на главную или пишем 403
        abort(403, 'ACCESS_DENIED: Unauthorized Node');
    }
}
