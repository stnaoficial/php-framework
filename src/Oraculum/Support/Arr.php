<?php

namespace Oraculum\Support;

use Oraculum\Support\Traits\NonInstantiable;

final class Arr
{
    use NonInstantiable;

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

    /**
     * Returns the last item and return an list with their value and key.
     * 
     * @param  array $items The items to fetch.
     * 
     * @return array{value: mixed, key: array-key} The last item.
     */
    public static function last($items)
    {
        return ['value' => end($items), 'key' => key($items)];
    }
}