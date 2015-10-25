<?php
/**
 * Helper class to access to env variables
 */
namespace Argentina\Helper;


class Env
{

    /**
     * Get env key
     * @param string $key key name
     * @param bool|false $default default value to set
     *
     * @return bool|string
     */
    public static function get($key, $default = false)
    {
        if (empty($key)) {
            return $default;
        }

        $val = getenv($key);

        if ($val === false) {
            $val = getenv(strtoupper($key));
        }

        if ($val === false) {
            $val = getenv(strtolower($key));
        }

        // If not found, return default value
        if ($val === false) {
            return $default;
        }

        // Cast false and no as boolean
        if ($val === 'false' || $val === 'no') {
            return false;
        }

        // Cast true and yes as boolean
        if ($val === 'true' || $val === 'yes') {
            return true;
        }

        return $val;
    }

}