<?php

namespace Modules\Support;

class Region
{
    /**
     * Path of the resource.
     *
     * @var string
     */
    const RESOURCE_PATH = __DIR__ . '/Resources/regions.php';

    /**
     * Array of all regions.
     *
     * @var array
     */
    private static $regions;

    /**
     * Get all regions.
     *
     * @return array
     */
    public static function all()
    {
        if (is_null(self::$regions)) {
            self::$regions = require self::RESOURCE_PATH;
        }

        $allRegions = [];

        foreach(self::$regions as $key => $region) {
            $allRegions[$key] = $region['name'];
        }

        return $allRegions;
    }

    /**
     * Get all region codes.
     *
     * @return array
     */
    public static function codes()
    {
        return array_keys(self::all());
    }

    /**
     * Get all region codes.
     *
     * @return array
     */
    public static function byStateCode($code)
    {
        if (is_null(self::$regions)) {
            self::$regions = require self::RESOURCE_PATH;
        }

        foreach(self::$regions as $key => $region) {
            if(in_array($code, $region['states'])) {
                return $key;
            }
        }

        return '';
    }

    /**
     * Get name of the given region code.
     *
     * @param string $code
     * @return string
     */
    public static function name($code)
    {
        return array_get(self::all(), $code);
    }

    /**
     * Get supported regions.
     *
     * @return array
     */
    public static function supported($supportedRegions)
    {
        return array_filter(static::all(), function ($code) use ($supportedRegions) {
            return in_array($code, $supportedRegions);
        }, ARRAY_FILTER_USE_KEY);
    }
}
