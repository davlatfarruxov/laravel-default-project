<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** Qo'llab-quvvatlanadigan tillar. */
    public const SUPPORTED = ['uz', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', 'uz');

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = 'uz';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
