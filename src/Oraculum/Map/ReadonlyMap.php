<?php

namespace Oraculum\Map;

use Iterator;
use Oraculum\Contracts\Arrayable;
use Oraculum\Support\Arr as ArraySupport;
use Oraculum\Support\Primitives\PrimitiveObject;

/**
 * @template TKey
 * @template TValue
 * @implements Iterator<TKey, TValue>
 */
final class ReadonlyMap extends PrimitiveObject implements Arrayable, Iterator
{
    /**
     * The items.
     * 
     * @var array<Tkey, TValue>
     */
    private $items = [];

    /**
     * Creates a new instance of the class.
     *
     * @param array<Tkey, TValue> $items The items.
     * 
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    /**
     * Gets a array representation of the object.
     * 
     * @return array<Tkey, TValue> Returns the `array` representation of the object.
     */
    public function toArray()
    {
        return $this->items;
    }
    
    /**
     * Returns the array length.
     * 
     * @return int Returns the array length.
     */
    public function length()
    {
        return count($this->items);
    }

    /**
     * Returns an instance of keys.
     * 
     * @return array<TKey> The keys.
     */
    public function keys()
    {
        return array_keys($this->items);
    }

    /**
     * Returns an instance of values.
     * 
     * @return array<TValue> The values.
     */
    public function values()
    {
        return array_values($this->items);
    }

    /**
     * Gets an item.
     * 
     * @param TKey $key The key to get
     * 
     * @return TValue|null Returns the value on success, or `null` on failure.
     */
    public function get($key)
    {    
        return $this->contains($key)? $this->items[$key] : null;
    }

    /**
     * Gets a default value for an item until it exists.
     * 
     * @template TDefaultValue of TValue
     * 
     * @param TKey          $key   The key to check.
     * @param TDefaultValue $value The default value.
     * 
     * @return TDefaultValue|TValue Returns the current value or the default if not found.
     */
    public function until($key, $value)
    {
        return $this->contains($key)? $this->get($key) : $value;
    }

    /**
     * Returns the first item.
     * 
     * @return TValue|null Returns the first item on success, or `null` on failure.
     */
    public function first()
    {
        $items = $this->items;

        if ($item = reset($items)) {
            return $item;
        }

        return null;
    }

    /**
     * Returns the last item.
     * 
     * @return TValue|null Returns the last item on success, or `null` on failure.
     */
    public function last()
    {
        $items = $this->items;

        if ($item = end($items)) {
            return $item;
        }

        return null;
    }

    /**
     * Returns an instance with the items remaining.
     * 
     * @return array<Tkey, TValue> The items remaining.
     */
    public function remaining()
    {
        $this->next();

        $offset = array_search($this->key(), $this->keys(), true);

        return array_slice($this->items, $offset, null, true);
    }

    /**
     * Returns all items.
     * 
     * @return array<Tkey, TValue> The items.
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Verifies if the array includes some of the values provided.
     * 
     * @param TValue $values The values to check.
     * 
     * @return bool Returns `true` on success or `false` on failure.
     */
    public function includes(...$values)
    {
        return empty(array_diff($values, $this->values()));
    }

    /**
     * Verifies if the array contains some of the keys provided.
     * 
     * @param TKey $keys The keys to check.
     * 
     * @return bool Returns `true` on success or `false` on failure.
     */
    public function contains(...$keys)
    {
        return empty(array_diff($keys, $this->keys()));
    }

    /**
     * Verifies if is empty.
     * 
     * @return bool Returns `true` on success or `false` on failure.
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Check if an item is true.
     * 
     * @param TKey $name The name of the item.
     * 
     * @return bool Returns `true` on success or `false` on failure.
     */
    public function isTrue($name)
    {
        return $this->get($name) === true;
    }

    /**
     * Returns a list of values from a single column.
     * 
     * @param TKey|null $key The key of the column.
     * 
     * @template TNewValue of TValue
     * 
     * @return array<int, TNewValue> A list of values from a single column.
     */
    public function pluck($key)
    {
        return array_column($this->items, $key);
    }

    /**
     * Returns a list of key|value pairs.
     * 
     * @param TKey|null $key   The key of the column.
     * @param TKey|null $value The value of the column.
     * 
     * @template TNewKey of TKey
     * @template TNewValue of TValue
     * 
     * @return array<TNewKey, TNewValue> A list of key|value pairs.
     */
    public function pair($key, $value)
    {
        return array_column($this->items, $value, $key);
    }

    /**
     * Flatten items in a specified depth.
     * 
     * @param int $depth The depth to flatten.
     * 
     * @template TNewKey of TKey
     * @template TNewValue of TValue
     * 
     * @return array<TNewKey, TNewValue> The flattened items.
     */
    public function flat($depth = INF)
    {
        return ArraySupport::flatten($this->items, $depth);
    }

    /**
	 * Returns the current item.
     * 
	 * @return TValue Can return any type.
	 */
	public function current(): mixed
    {
        return current($this->items);
    }

	/**
	 * Returns the key of the current item.
     * 
	 * @return TKey|null Returns `scalar` on success, or `null` on failure.
	 */
	public function key(): mixed
    {
        return key($this->items);
    }

	/**
	 * Move forward to next item.
	 * 
     * Moves the current position to the next item.
     * 
	 * @return void
	 */
	public function next(): void
    {
        next($this->items);
    }

	/**
	 * Rewind the Iterator to the first item.
     * 
	 * Rewinds back to the first item of the Iterator.
     * 
	 * @return void
	 */
	public function rewind(): void
    {
        reset($this->items);
    }

	/**
	 * Checks if current position is valid.
	 * 
     * This method is called after Iterator::rewind() and Iterator::next() to check
     * if the current position is valid.
     * 
	 * @return bool The return value will be casted to `bool` and then evaluated
     *              Returns `true` on success or `false` on failure.
	 */
	public function valid(): bool
    {
        return key($this->items) !== null;
    }
}