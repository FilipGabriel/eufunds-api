<?php

/*
 * (c) Samuel De Backer <sdebacker@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Modules\Support;

use App;
use Illuminate\Support\Arr;
use TypiCMS\NestableCollection as BaseNestableCollection;

class NestableCollection extends BaseNestableCollection
{

    /**
     * Nest items.
     *
     * @return mixed NestableCollection
     */
    public function nestIds($ids)
    {
        $parentColumn = $this->parentColumn;
        if (!$parentColumn) {
            return $this;
        }

        // Set id as keys.
        $this->items = $this->getDictionary();

        $keysToDelete = [];

        // Add empty collection to each items.
        $collection = $this->each(function ($item) {
            if (!$item->{$this->childrenName}) {
                $item->{$this->childrenName} = app()->make('Illuminate\Support\Collection');
            }
        });

        // Remove items with missing ancestor.
        if ($this->removeItemsWithMissingAncestor) {
            $collection = $this->reject(function ($item) use ($parentColumn) {
                if ($item->{$parentColumn}) {
                    $missingAncestor = $this->anAncestorIsMissing($item);

                    return $missingAncestor;
                }
            });
        }

        foreach (array_filter($collection->items, function($item) use ($ids) {
            return in_array($item->id,$ids);
        }) as $key => $item) {
            if(in_array($item->id, $ids) && ! in_array($item->{$parentColumn}, $ids) && $item->{$parentColumn}) {
                $ids[] = $item->{$parentColumn};
            }
        }

        // Add items to children collection.
        foreach ($collection->items as $key => $item) {
            if (in_array($item->id, $ids) && $item->{$parentColumn} && isset($collection[$item->{$parentColumn}])) {
                $collection[$item->{$parentColumn}]->{$this->childrenName}->push($item);
                $keysToDelete[] = $item->id;
            }

            if(! in_array($item->id, $ids)) {
                $keysToDelete[] = $item->id;
            }
        }

        // Delete moved items.
        $this->items = array_values(Arr::except($collection->items, $keysToDelete));

        return $this;
    }

    private function notHaveChildrens($item, $collection, $ids)
    {
        foreach ($item->items as $key => $children) {
            if($children->items->isEmpty() && ! in_array($children->id, $ids)) {
                return true;
            } else {
                $this->notHaveChildrens($children->items, $collection, $ids);
            }
        }

        return false;
    }
    
}
