<?php

namespace Modules\Support;

use Transliterator;

class State
{
    /**
     * Path of the resource.
     *
     * @var string
     */
    const RESOURCE_PATH = __DIR__ . '/Resources/states';

    /**
     * Array of states.
     *
     * @var array
     */
    private static $states;

    /**
     * Get all states of the given country code.
     *
     * @param string $code
     * @return array|null
     */
    public static function get($code)
    {
        if (isset(self::$states[$code])) {
            return self::$states[$code];
        }

        $path = self::RESOURCE_PATH . "/{$code}.php";

        if (file_exists($path)) {
            return self::$states[$code] = require $path;
        }
    }

    /**
     * Get all states of the given country code.
     *
     * @param string $code
     * @return array|null
     */
    public static function getForCheckout($code)
    {
        if (isset(self::$states[$code])) {
            return self::$states[$code];
        }

        $path = self::RESOURCE_PATH . "/{$code}.php";

        if (file_exists($path)) {
            self::$states[$code] = require $path;

            return collect(self::$states[$code])->map(function($name, $code) {
                return [
                    'code' => $code,
                    'name' => $name
                ];
            })->values()->toArray();
        }
    }

    public static function name($countryCode, $stateCode)
    {
        return array_get(self::get($countryCode), $stateCode);
    }

    public static function code($countryCode, $stateName)
    {
        $transliterator = Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: Lower(); :: NFC;', Transliterator::FORWARD);
        $stateName = $transliterator->transliterate($stateName);

        foreach (self::get($countryCode) as $key => $value) {
            if (false !== stripos($value, $stateName)) {
                return $key;
            }
        }

        return '';
    }
}
