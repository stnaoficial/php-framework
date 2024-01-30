<?php

namespace Oraculum\Json;

use Oraculum\Contracts\Arrayable;
use Oraculum\Contracts\FromArray;
use Oraculum\Contracts\Media;
use Oraculum\Contracts\Stringable;
use Oraculum\Http\Exceptions\InvalidJsonException;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Json extends PrimitiveObject implements Media, FromArray, Arrayable, Stringable
{
    /**
     * @var string The source of the JSON.
     */
    private $source;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $source The source of the JSON.
     * 
     * @return void
     */
    public function __construct($source = "{}")
    {
        $this->source = $source;
    }

    /**
     * Creates a new instance from an array.
     * 
     * @param array $array The array to create the instance.
     * 
     * @throws InvalidJsonException If the array could not be encoded to JSON.
     * 
     * @return self The new instance.
     */
    public static function fromArray($array)
    {
        $source = json_encode($array, JSON_PRETTY_PRINT);

        if (!is_string($source) || json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(
                "Unable to encode array to JSON.",
            );
        }

        return new self($source);
    }

    /**
     * Gets a array representation of the object.
     * 
     * @return array Returns the `array` representation of the object.
     */
    public function toArray()
    {
        return (array) json_decode($this->source, true);
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->source;
    }

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Gets the byte size of the JSON.
     * 
     * @return int Returns the byte size of the JSON.
     */
    public function getByteSize()
    {
        $source = json_encode($this, JSON_NUMERIC_CHECK);

        if (!is_string($source) || json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(
                "Unable to numerically encode JSON to get the size.",
            );
        }

        return mb_strlen($source, '8bit');
    }

    /**
     * Gets the size of the JSON.
     * 
     * @return int Returns the size of the JSON.
     */
    public function getSize()
    {
        return strlen($this->source);
    }
}