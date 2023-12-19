<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Services;

class BlacklistFilter
{
    public static function filter(array $values, array $loggableFields): array
    {
        $filtered = $values;
        $filtered = self::filterBlacklist($filtered);

        if (reset($loggableFields) === '*') {
            return $filtered;
        }

        return self::filterNonLoggable($loggableFields, $filtered);
    }

    public static function filterBlacklist(array $values): array
    {
        $filtered = $values;

        /**
         * @var array<array-key, string> $blacklistedKeys
         */
        $blacklistedKeys = config('footprints.content.blacklisted_keys');
        foreach ($blacklistedKeys as $blacklistedKey) {
            if (array_key_exists($blacklistedKey, $filtered)) {
                $filtered[$blacklistedKey] = '******';
            }
        }

        return $filtered;
    }

    public static function filterNonLoggable(array $loggableFields, array $values): array
    {
        $filtered = $values;

        foreach (array_keys($filtered) as $property) {
            if (! in_array($property, $loggableFields)) {
                unset($filtered[$property]);
            }
        }

        return $filtered;
    }
}
