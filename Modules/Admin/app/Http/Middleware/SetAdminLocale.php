<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');
        if (!$locale && Auth::guard('admin')->check()) {
            $locale = Auth::guard('admin')->user()->lang;
            $request->session()->put('locale', $locale);
        }
        app()->setLocale($locale ?? config('app.locale'));

        return $next($request);
    }
}
