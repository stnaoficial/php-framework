<?php

namespace Oraculum\Alias;

use BadMethodCallException;
use InvalidArgumentException;
use Oraculum\Support\Contracts\FromArray;

final class Alias implements FromArray
{
    /**
     * @var string The alias name.
     */
    private $name;

    /**
     * @var mixed The alias value.
     */
    private $value;

    /**
     * @var bool Determines whether the alias can be overwritten.
     */
    private $overwrite;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $name      The alias name.
     * @param mixed  $value     The alias value.
     * @param bool   $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws InvalidArgumentException If the alias name is not a string.
     * 
     * @return void
     */
    public function __construct($name, $value = null, $overwrite = true)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException("The alias name must be a string.");
        }

        $this->name      = $name;
        $this->value     = $value;
        $this->overwrite = is_bool($overwrite)? $overwrite : true;
    }

    /**
     * Creates a new instance from an array.
     * 
     * @param array $array The array to create the instance.
     * 
     * @throws InvalidArgumentException If the array does not specify a name and a value.
     * 
     * @return self The new instance.
     */
    public static function fromArray($array)
    {
        if (!isset($array["name"], $array["value"])) {
            throw new InvalidArgumentException(
                "The alias must have a name and a value."
            );
        }

        return new self(
            $array["name"],
            $array["value"],
            $array["overwrite"] ?? true
        );
    }

    /**
     * Gets the alias name.
     * 
     * @return string The alias name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the alias value.
     * 
     * @return mixed The alias value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the alias value.
     * 
     * @param mixed $value The alias value.
     * 
     * @throws BadMethodCallException If the alias cannot be overwritten.
     * 
     * @return void
     */
    public function setValue($value)
    {
        if (!$this->overwrite) {
            throw new BadMethodCallException(sprintf(
                "Alias [%s] cannot be overwritten.", $this->name
            ));
        }

        $this->value = $value;
    }

    /**
     * Determines whether the alias can be overwritten.
     * 
     * @return bool Determines whether the alias can be overwritten.
     */
    public function isOverwritable()
    {
        return $this->overwrite;
    }
}