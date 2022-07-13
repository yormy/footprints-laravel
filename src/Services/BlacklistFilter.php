<?php
namespace Yormy\LaravelFootsteps\Services;

class BlacklistFilter
{
    public static function filter(array $values): string
    {
        $filtered = $values;
        $blacklistedKeys = config('footsteps.blacklisted_keys');
        foreach ($blacklistedKeys as $blacklistedKey) {
            if (array_key_exists($blacklistedKey, $filtered)) {
                $filtered[$blacklistedKey] = "******";
            }
        }

        return json_encode($filtered);
    }
}
