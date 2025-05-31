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
}
