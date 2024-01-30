<?php

namespace Oraculum\Container;

use Closure;
use Oraculum\Container\Exceptions\BindingResolutionException;
use Oraculum\Container\Support\BoundMethod;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use Oraculum\Support\Traits\NonInstantiable;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

final class Container extends PrimitiveObject
{
    use NonInstantiable, GloballyAvailable;

    /**
     * All bindings registered.
     * 
     * @var array<string, array{concrete: Closure|string, shared: bool}>
     */
    protected $bindings = [];

    /**
     * All instances registered.
     * 
     * @var array<string, object>
     */
    protected $instances = [];

    /**
     * Register a binding.
     * 
     * @param string              $abstract The abstract type.
     * @param Closure|string|null $concrete The concrete type.
     * @param bool                $shared   Whether the binding is shared.
     *  
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        if (is_null($concrete)) {
            $concrete = $abstract;
        }

        $this->bindings[$abstract] = compact('concrete', 'shared');
    }
    
    /**
     * Register a shared bindind.
     * 
     * @param string              $abstract The abstract type.
     * @param Closure|string|null $concrete The concrete type.
     * 
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * Determine if the given abstract type has been bound.
     * 
     * @param string $abstract The abstract type.
     * 
     * @return bool Returns `true` if the type is bound, `false` otherwise.
     */
    public function bound($abstract)
    {
        return isset($this->bindings[$abstract]);
    }

    /**
     * Gets the concrete type for a given abstract.
     * 
     * @param string $abstract The abstract type.
     * 
     * @return Closure|string The concrete type.
     */
    protected function getConcrete($abstract)
    {   
        if ($this->bound($abstract)) {
            return $this->bindings[$abstract]['concrete'];
        }

        return $abstract;
    }

    /**
     * Determines if the given abstract type is shared.
     * 
     * @param string $abstract The abstract type.
     * 
     * @return bool Returns `true` if the type is shared, `false` otherwise.
     */
    public function isShared($abstract)
    {
        if ($this->bound($abstract)) {
            return $this->bindings[$abstract]['shared'];
        }

        return false;
    }

    /**
     * Determine if the given abstract type has been resolved.
     * 
     * @param string $abstract The abstract type.
     * 
     * @return bool Returns `true` if the type is resolved, `false` otherwise.
     */
    public function resolved($abstract)
    {
        return isset($this->instances[$abstract]);
    }

    /**
     * Get the class name of the given parameter type, if possible.
     *
     * @param ReflectionParameter $parameter The reflection parameter.
     * 
     * @return string|null The class name if possible, `null` otherwise.
     */
    private static function getParameterClassName($parameter)
    {
        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
            return null;
        }

        $name = $type->getName();

        if (!is_null($class = $parameter->getDeclaringClass())) {
            if ($name === 'self') {
                return $class->getName();
            }

            if ($name === 'parent' && $parent = $class->getParentClass()) {
                return $parent->getName();
            }
        }

        return $name;
    }

    /**
     * Resolve an ReflectionParameter dependency.
     * 
     * @param ReflectionParameter $dependency The reflection parameter.
     * 
     * @throws BindingResolutionException If the dependency cannot be resolved.
     * 
     * @return mixed The resolved dependency.
     */
    private function resolveDependency($dependency)
    {
        if ($className = $this->getParameterClassName($dependency)) {
            return $this->resolve($className);
        }
        
        if ($dependency->isDefaultValueAvailable()) {
            return $dependency->getDefaultValue();
        }

        if ($declaringClass = $dependency->getDeclaringClass()) {
            throw new BindingResolutionException(sprintf(
                "Unresolvable dependency [$%s] in class [%s]", $dependency->getName(), $declaringClass->getName()
            ));
        }
        
        throw new BindingResolutionException(sprintf(
            "Unresolvable dependency [$%s]", $dependency->getName()
        ));
    }

    /**
     * Resolve all ReflectionParameters dependencies.
     * 
     * @param ReflectionParameter[] $dependencies         The reflection parameters.
     * @param array                 $resolvedDependencies The resolved dependencies.
     * 
     * @throws BindingResolutionException If the dependency cannot be resolved.
     * 
     * @return array The resolved dependencies.
     */
    private function resolveDependencies($dependencies, $resolvedDependencies = [])
    {
        foreach (array_reverse($dependencies) as $dependency) {
            if (
                $dependency->isVariadic() ||
                $dependency->isOptional() ||
                array_key_exists($dependency->getName(), $resolvedDependencies)
            ) {
                continue;
            }

            $resolvedDependency = $this->resolveDependency($dependency);
        
            array_unshift($resolvedDependencies, $resolvedDependency);
        }

        return $resolvedDependencies;
    }

    /**
     * Instantiate a concrete instance of the given type.
     * 
     * @param Closure|string $concrete     The concrete type.
     * @param array          $dependencies The dependencies to inject.
     * 
     * @throws BindingResolutionException If the concrete type is not instantiable.
     * 
     * @return object The new instance.
     */
    protected function build($concrete, $dependencies = [])
    {
        if ($concrete instanceof Closure) {
            return $this->call($concrete);
        }

        if (!class_exists($concrete)) {
            throw new BindingResolutionException(
                "Target class [$concrete] does not exist."
            );
        }

        $reflection = new ReflectionClass($concrete);
    
        if (!$reflection->isInstantiable()) {
            throw new BindingResolutionException(
                "Class [$concrete] is not instantiable"
            );
        }

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return $reflection->newInstanceWithoutConstructor();
        }

        $resolvedDependencies = $this->resolveDependencies($constructor->getParameters(), $dependencies);

        return $reflection->newInstanceArgs(array_values($resolvedDependencies));
    }

    /**
     * Resolve the given type.
     * 
     * @param string $abstract     The abstract type.
     * @param array  $dependencies The dependencies to inject.
     * 
     * @throws BindingResolutionException If the type cannot be resolved.
     * 
     * @return object The resolved instance.
     */
    public function resolve($abstract, $dependencies = []) 
    {
        if ($this->resolved($abstract)) {
            return $this->instances[$abstract];
        }

        $instance = $this->build($this->getConcrete($abstract), $dependencies);

        if (!$this->resolved($abstract)) {
            $this->instances[$abstract] = $instance;
        }

        return $this->instances[$abstract];
    }

    /**
     * Call the given Closure and inject its dependencies.
     * 
     * @param Closure|array|string $callback     The callback to call.
     * @param array                $dependencies The dependencies to inject.
     * 
     * @throws BindingResolutionException If the dependency cannot be resolved.
     * 
     * @return mixed The result of the callback.
     */
    public function call($callback, $dependencies = [])
    {
        $callback = BoundMethod::closure($this, $callback);

        $reflection = new ReflectionFunction($callback);
        
        $resolvedDependencies = $this->resolveDependencies($reflection->getParameters(), $dependencies);

        return $reflection->invokeArgs(array_values($resolvedDependencies));
    }
}