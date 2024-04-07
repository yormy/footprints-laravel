<?php

declare(strict_types=1);

namespace Yormy\FootprintsLaravel\Services;

class RuleService
{
    public static function shouldIgnore(string $string, array $ignoreRules): bool
    {
        return self::doesMatch($string, $ignoreRules);
    }

    public static function shouldInclude(string $string, array $includeRules): bool
    {
        return self::doesMatch($string, $includeRules);
    }

    private static function doesMatch(string $string, array $rules): bool
    {
        /**
         * @var array<array-key, string> $rules
         */
        foreach ($rules as $rule) {
            if (fnmatch($rule, $string)) {
                return true;
            }
        }

        return false;
    }
}
