<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Services\Resolvers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UserAgentResolver
{
    public static function get(): string
    {
        $agent = new Agent();
        $platform = $agent->platform();
        $userAgent = $platform.' '.$agent->version($platform);

        $browser = $agent->browser();
        $userAgent .= $browser.' '.$agent->version($browser);

        return $userAgent;
    }

    public static function getFullAgent(Request $request): string
    {
        return $request->server('HTTP_USER_AGENT');
    }
}
