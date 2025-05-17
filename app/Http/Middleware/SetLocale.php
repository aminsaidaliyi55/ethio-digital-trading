<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{

    public function handle(Request $request, Closure $next)
    {
            $request->session()->start(); // 🚨 Force session start

        $locale = session('locale', config('app.locale'));

        // Debug log to check what locale is being set
        Log::info('Locale set to: ' . $locale); // ✅ this logs into storage/logs/laravel.log

        App::setLocale($locale);

        return $next($request);
    }
}
