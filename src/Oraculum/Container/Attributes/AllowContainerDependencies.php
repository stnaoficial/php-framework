<?php

namespace Oraculum\Container\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
class AllowContainerDependencies
{
    /**
     * @var array<int, string> The dependencies to allow.
     */
    private $dependencies = [];
    
    /**
     * Creates a new instance of the class.
     * 
     * @param array $dependencies The dependencies to allow.
     * 
     * @return void
     */
    public function __construct($dependencies)
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Get all allowed dependencies.
     * 
     * @return array<int, string> Returns the allowed dependencies.
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
}