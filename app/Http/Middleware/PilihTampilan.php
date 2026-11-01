<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class PilihTampilan
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('tampilan') && in_array($request->get('tampilan'), ['web', 'hp'], true)) {
            session(['tampilan' => $request->get('tampilan')]);
        }
        $mode = session('tampilan');
        if (! in_array($mode, ['web', 'hp'], true)) {
            // Lingkungan testing: default hp agar deterministik.
            // Mode web diuji eksplisit via ?tampilan=web / session.
            if (app()->environment('testing')) {
                $mode = 'hp';
            } else {
                $agent = new Agent;
                // Bot/crawler/UA tak dikenal → default hp (aman).
                if ($agent->isRobot()) {
                    $mode = 'hp';
                } elseif ($agent->isMobile() || $agent->isTablet()) {
                    $mode = 'hp';
                } elseif ($agent->isDesktop()) {
                    $mode = 'web';
                } else {
                    $mode = 'hp';
                }
            }
            session(['tampilan' => $mode]);
        }
        view()->share('tampilan', $mode);
        view()->share('is_web', $mode === 'web');

        return $next($request);
    }
}
