<?php

namespace Modules\Support\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Modules\Support\Region;
use Modules\Support\RegionV2;
use Modules\Support\State;

class CountryStateController
{
    /**
     * Display a listing of the resource.
     *
     * @param string $countryCode
     * @return JsonResponse
     */
    public function index($countryCode)
    {
        $states = State::get($countryCode);

        return response()->json($states);
    }

    public function fullStates($countryCode)
    {
        $states = State::get($countryCode);

        $results = [];
        foreach ($states as $stateCode => $stateName) {
            $regionCode = Region::byStateCode($stateCode);
            $results[$stateCode] = [
                'stateCode' => $stateCode,
                'stateName' => $stateName,
                'regionCode' => $regionCode,
                'regionName' => Region::name($regionCode),
            ];
        }

        return response()->json($results);
    }

    public function statesAndRegions($countryCode)
    {
        $states = State::get($countryCode);

        $results = [];
        foreach ($states as $key => $stateName) {
            $regionCode = RegionV2::byStateCode($key);
            $stateCode = RegionV2::getStateCode($key);
            $results[$key] = [
                'stateCode' => $stateCode,
                'stateName' => $stateName,
                'regionCode' => $regionCode,
                'regionName' => RegionV2::name($key),
            ];
        }

        return response()->json($results);
    }

    public function getStateRegionCodes($stateKey)
    {
        $states = State::get('RO');

        $results = [];
        foreach ($states as $key => $name) {
            if($stateKey === $key){
                $regionCode = RegionV2::byStateCode($key);
                $stateCode = RegionV2::getStateCode($key);
                $stateName = RegionV2::name($key);
                return [
                    'stateCode' => $stateCode,
                    'stateName' => $stateName,
                    'regionCode' => $regionCode,
                    'regionName' => RegionV2::name($key),
                ];
            }
        }

        return response()->json($results);
    }
}
