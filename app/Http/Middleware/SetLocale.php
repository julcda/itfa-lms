<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: URL param > session > user preference > default
        if ($request->has('lang')) {
            $locale = $request->get('lang');
        } elseif (session()->has('locale')) {
            $locale = session('locale');
        } elseif (Auth::check() && Auth::user()->locale) {
            $locale = Auth::user()->locale;
        } else {
            $locale = config('app.locale');
        }

        $allowed = ['en', 'ar'];
        $locale = in_array($locale, $allowed) ? $locale : 'en';

        App::setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
