<?php

namespace Oraculum\Json;

use Oraculum\Support\Contracts\Arrayable;
use Oraculum\Support\Contracts\FromArray;
use Oraculum\Support\Contracts\Stringable;
use Oraculum\Http\Exceptions\InvalidJsonException;
use Oraculum\Json\Support\Json as JsonSupport;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Json extends PrimitiveObject implements FromArray, Arrayable, Stringable
{
    /**
     * @var string $json The JSON.
     */
    private $json;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $json The JSON.
     * 
     * @return void
     */
    public function __construct($json = '{}')
    {
        $this->json = $json;
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
        $json = @json_encode($array, JSON_PRETTY_PRINT);

        if (!is_string($json) || @json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(
                "Unable to encode array to JSON.",
            );
        }

        return new self($json);
    }

    /**
     * Gets a array representation of the object.
     * 
     * @return array Returns the `array` representation of the object.
     */
    public function toArray()
    {
        return (array) @json_decode($this->json, true);
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return (string) @json_encode(@json_decode($this->json), JSON_PRETTY_PRINT);
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
     * @param string $encoding The encoding to use.
     * 
     * @return int Returns the byte size of the JSON.
     */
    public function getByteSize($encoding = '8bit')
    {
        $json = @json_encode($this, JSON_NUMERIC_CHECK);

        if (!is_string($json) || @json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(
                "Unable to numerically encode JSON to get the size.",
            );
        }

        return mb_strlen($json, $encoding);
    }

    /**
     * Gets the size of the JSON.
     * 
     * @return int Returns the size of the JSON.
     */
    public function getSize()
    {
        return strlen($this->json);
    }

    /**
     * Checks if the JSON is valid.
     * 
     * @param int $depth The depth to check.
     * 
     * @return bool Returns `true` if the JSON is valid, otherwise `false`.
     */
    public function isValid($depth = 512)
    {
        return JsonSupport::validate($this->json, $depth);
    }
}