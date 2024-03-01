<?php

namespace Oraculum\Alias;

use InvalidArgumentException;
use Oraculum\Support\Contracts\FromArray;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;

class Network extends PrimitiveObject implements FromArray
{
    use GloballyAvailable;

    /**
     * @var array<string, mixed> The aliases of the network.
     */
    private $aliases;

    /**
     * Create a new instance of the class.
     * 
     * @param array<string, mixed> $aliases The aliases of the network.
     * 
     * @throws InvalidArgumentException If some alias is not valid.
     * 
     * @return void
     */
    public function __construct($aliases = [])
    {
        $this->load($aliases);
    }

    /**
     * Creates a new instance from an array.
     * 
     * @param array $array The array to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromArray($array)
    {
        return new self($array);
    }

    /**
     * Gets the aliases of the network.
     * 
     * @return array<string, mixed> The aliases of the network.
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * Determines if an alias exists.
     * 
     * @param string $name The alias name.
     * 
     * @return bool Returns `true` if the alias exists, `false` otherwise.
     */
    public function hasAlias($name)
    {
        return isset($this->aliases[$name]);
    }

    /**
     * Gets an alias.
     * 
     * @template TValue
     * 
     * @param string $name The alias name.
     * 
     * @throws InvalidArgumentException If the alias does not exist.
     * 
     * @return TValue The alias value.
     */
    public function getAlias($name)
    {
        if (!$this->hasAlias($name)) {
            throw new InvalidArgumentException(sprintf(
                "Alias [%s] does not exist.", $name
            ));
        }

        return $this->aliases[$name]->getValue();
    }

    /**
     * Sets an alias.
     * 
     * @param Alias $newAlias The new alias to set.
     * 
     * @throws BadMethodCallException If the alias cannot be overwritten.
     * 
     * @return void
     */
    public function setAlias($newAlias)
    {
        $name = $newAlias->getName();

        if (!$this->hasAlias($name)) {
            $this->aliases[$name] = $newAlias;
            return;
        }

        /** @var Alias */
        $alias = $this->aliases[$name];

        $alias->setValue($newAlias->getValue());

        $this->aliases[$name] = $alias;
    }

    /**
     * Gets or sets an alias.
     * 
     * @template TValue
     * 
     * @param string $name      The alias name.
     * @param TValue $value     The alias value.
     * @param bool   $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws InvalidArgumentException If the alias does not exist.
     * @throws BadMethodCallException   If the alias cannot be overwritten.
     * 
     * @return TValue The alias value.
     */
    public function alias($name, $value = null, $overwrite = true)
    {
        if (is_null($value)) {
            return $this->getAlias($name);
        }

        $this->setAlias(new Alias($name, $value, $overwrite));

        return $value;
    }

    /**
     * Loads the given aliases into the network.
     * 
     * @param array<string, mixed> $aliases The aliases to load.
     * 
     * @throws InvalidArgumentException If some alias is not valid.
     * 
     * @return void
     */
    public function load($aliases = [])
    {
        foreach ($aliases as $name => $value) {
            if (is_array($value)) {
                $value['name'] = $name;
            }

            $alias = is_array($value)? Alias::fromArray($value) : new Alias($name, $value);

            $this->setAlias($alias);
        }
    }
}