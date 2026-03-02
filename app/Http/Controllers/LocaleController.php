<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $allowed = ['en', 'ar'];
        $locale = in_array($locale, $allowed) ? $locale : 'en';

        session(['locale' => $locale]);

        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }

        return redirect()->back()->withHeaders([
            'Vary' => 'Accept-Language',
        ]);
    }
}
