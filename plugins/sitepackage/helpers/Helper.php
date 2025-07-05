<?php

namespace dvll\Sitepackage\Helpers;

use Kirby\Toolkit\Str;

/**
 * Collection of helper methods
 *
 */
class Helper
{

    const KIRBYLOG_LVL_DEBUG = "DEBUG";
    const KIRBYLOG_LVL_INFO = "INFO";
    const KIRBYLOG_LVL_NOTICE = "NOTICE";
    const KIRBYLOG_LVL_WARNING = "WARNING";
    const KIRBYLOG_LVL_ERROR = "ERROR";
    const KIRBYLOG_LVL_CRITICAL = "CRITICAL";
    const KIRBYLOG_LVL_ALERT = "ALERT";
    const KIRBYLOG_LVL_EMERGENCY = "EMERGENCY";

    /**
     * Get an environment variable as string or boolean with optional default value
     * @param string|bool $default
     * @return string|bool|void
     */
    static function getEnv(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Ensures each item in a structure array has a unique customuuid. If missing or duplicate, generates a new one.
     * Returns a new array, does not mutate the input.
     * @param array<string, mixed> $structure The structure array (e.g., from a YAML field)
     * @return array<string, mixed> The structure array with unique customuuids
     */
    public static function ensureUniqueCustomUuids(array $structure): array
    {
        $uuids = [];
        $result = [];
        foreach ($structure as $index => $item) {
            $newItem = $item;
            if (empty($newItem['customuuid']) || in_array($newItem['customuuid'], $uuids, true)) {
                $newItem['customuuid'] = \Kirby\Uuid\Uuid::generate();
            }
            $uuids[] = $newItem['customuuid'];
            $result[$index] = $newItem;
        }
        return $result;
    }
}
