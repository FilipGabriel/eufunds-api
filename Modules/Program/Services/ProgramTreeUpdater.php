<?php

namespace Modules\Program\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProgramTreeUpdater
{
    /**
     * Update program tree order.
     *
     * @param array $tree
     * @return void
     */
    public static function update(array $tree)
    {
        list($ids, $parentIdCases, $positionCases, $params) = static::getDataForQuery($tree);

        $sql = "UPDATE `programs` SET
            `parent_id` = CASE `id` {$parentIdCases} END,
            `position` = CASE `id` {$positionCases} END,
            `updated_at` = ?
        WHERE `id` IN ({$ids})";

        DB::update($sql, $params);

        Cache::tags('programs')->flush();
    }

    /**
     * Get data for update query.
     *
     * @param array $tree
     * @return array
     */
    private static function getDataForQuery(array $tree)
    {
        $params = [];
        $ids = [];

        foreach (static::getAttributesList($tree) as $id => $values) {
            foreach ($values as $column => $value) {
                $cases[$column][] = "WHEN {$id} THEN ?";
                $params[$column][] = $value;
            }

            $ids[] = $id;
        }

        return static::prepareData($ids, $cases, $params);
    }

    /**
     * Get attributes list from given tree.
     *
     * @param array $tree
     * @return array
     */
    private static function getAttributesList(array $tree)
    {
        $attributes = [];

        foreach ($tree as $position => $program) {
            $attributes[$program['id']] = [
                'parent_id' => $program['parent_id'],
                'position' => $position,
            ];
        }

        return $attributes;
    }

    /**
     * Prepare data for update query.
     *
     * @param array $ids
     * @param array $cases
     * @param array $params
     * @return array
     */
    private static function prepareData(array $ids, array $cases, array $params)
    {
        $ids = implode(',', $ids);
        $parentIdCases = implode(' ', $cases['parent_id']);
        $positionCases = implode(' ', $cases['position']);

        $params = array_flatten($params);
        $params[] = Carbon::now();

        return [$ids, $parentIdCases, $positionCases, $params];
    }
}
