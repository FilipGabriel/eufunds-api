<?php

namespace Modules\Support\Services;

use Modules\Support\Entities\Settlement;

class SettlementService
{
    public function settlements(bool $isCity = false): array
    {
        $settlement = Settlement::orderBy('name');
        if ($isCity) {
            $settlement = $settlement->whereNull('village');
        }

        $settlementsCollection = $settlement->get();

        $results = [];
        foreach ($settlementsCollection as $settlementsItem) {
            if (!array_key_exists($settlementsItem['county'], $results)) {
                $results[$settlementsItem['county']] = [];
            }

            $results[$settlementsItem['county']][$settlementsItem->full_name] = $settlementsItem->name;
        }

        ksort($results,  SORT_FLAG_CASE | SORT_STRING);

        return $results;
    }

    public function search(string $termsString, bool $isCity = false)
    {
        $response = [];
        if (
            preg_match_all("/\w+/", $termsString, $matches) &&
            array_key_exists(0, $matches)
        ) {
            $words = $matches[0];

            $settlement = new Settlement();
            foreach ($words as $word) {
                $settlement = $settlement->where('full_name', 'like', "%{$word}%");
            }

            if ($isCity) {
                $settlement = $settlement->whereNull('village');
            }

            $response = $settlement->get();
        }

        return $response;
    }
}
