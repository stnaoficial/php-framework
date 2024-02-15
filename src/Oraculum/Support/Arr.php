<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Arr
{
    use NonInstantiable;

    /**
     * Restore an item from an array.
     * 
     * @template TKey of array-key
     * @template TValue
     * 
     * @param  array<Tkey, TValue> $items The items reference where the item will be restored.
     * @param  TKey                $key   The key of the item to restore.
     * 
     * @return TValue The restored item.
     */
    public static function restore(&$items, $key)
    {
        $value = $items[$key] ?? null; unset($items[$key]);

        return $value;
    }

    /**
     * Flatten items in a specified depth.
     *
     * @param  array $items The items to flatten.
     * @param  int   $depth The maximum recursion depth.
     *
     * @return array The flattened items.
     */
    public static function flatten($items, $depth = INF)
    {
        $result = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                $result[] = $item;

            } else {
                if ($depth === 1) {
                    $values = array_values($item);
                
                } else {
                    $values = self::flatten($item, $depth - 1);
                }

                foreach ($values as $value) {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }
}