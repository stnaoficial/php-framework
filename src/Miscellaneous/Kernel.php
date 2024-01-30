<?php

namespace Miscellaneous;

use BadMethodCallException;
use InvalidArgumentException;
use Miscellaneous\Abstracts\ServiceProvider;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use Oraculum\Support\Traits\NonInstantiable;

final class Kernel extends PrimitiveObject
{
    use NonInstantiable, GloballyAvailable;

    /**
     * @var array<string, array{value: mixed, overwrite: bool}> The aliases network.
     */
    private $aliases = [];

    /**
     * @var array<class-string<ServiceProvider>, ServiceProvider> The service providers.
     */
    private $services = [];

    /**
     * @var bool Determines whether the kernel has booted.
     */
    private $booted = false;

    /**
     * Creates a new instance of the class.
     * 
     * @return void
     */
    private function __construct()
    {
        $this->registerBaseAliases();
        $this->registerBaseServices();
    }

    /**
     * Display errors.
     * 
     * @return void
     */
    private function displayErrors()
    {
        ini_set('display_errors', 1);
    }

    /**
     * Report arrors in levels.
     * 
     * @param int $levels
     * 
     * @return void
     */
    private function reportErrors(int $levels = E_ALL)
    {
        error_reporting($levels);
    }

    /**
     * Displays all errors.
     * 
     * @return void
     */
    private function showErrors()
    {
        $this->displayErrors();
        $this->reportErrors();
    }

    /**
     * Determines if an alias exists.
     * 
     * @param string $name The alias name.
     * 
     * @return bool Returns `true` if the alias exists, `false` otherwise.
     */
    private function hasAlias($name)
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
    private function getAlias($name)
    {
        if (!$this->hasAlias($name)) {
            throw new InvalidArgumentException(sprintf(
                "Alias [%s] does not exist.", $name
            ));
        }

        return $this->aliases[$name]['value'];
    }

    /**
     * Sets an alias.
     * 
     * @template TValue
     * 
     * @param string $name The alias name.
     * @param TValue $value The alias value.
     * @param bool   $overwrite Determines whether the alias can be overwritten.
     * 
     * @throws BadMethodCallException If the alias cannot be overwritten.
     * 
     * @return void
     */
    private function setAlias($name, $value, $overwrite = true)
    {
        if (!$this->hasAlias($name)) {
            $this->aliases[$name] = compact('value', 'overwrite');
            return;
        }

        $alias = $this->aliases[$name];

        if (!$alias["overwrite"]) {
            throw new BadMethodCallException(sprintf(
                "Alias [%s] cannot be overwritten.", $name
            ));
        }

        $alias["value"] = $value;

        $this->aliases[$name] = $alias;
    }

    /**
     * Gets or sets an alias.
     * 
     * @template TValue
     * 
     * @param string      $name The alias name.
     * @param TValue|null $value The alias value.
     * @param bool        $overwrite Determines whether the alias can be overwritten.
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

        $this->setAlias($name, $value, $overwrite);

        return $value;
    }

    /**
     * Determines whether the context has the service.
     * 
     * @param class-string<ServiceProvider> $name The service class name.
     * 
     * @return bool Returns true if the service exists, false otherwise.
     */
    private function hasService($name)
    {
        return isset($this->services[$name]);
    }

    /**
     * Gets an service in the context.
     * 
     * @param class-string<ServiceProvider> $name The service class name.
     * 
     * @return ServiceProvider|null Returns the service if it exists, null otherwise.
     */
    private function getService($name)
    {
        return $this->hasService($name)? $this->services[$name] : null;
    }

    /**
     * Sets the given service.
     *
     * @param ServiceProvider $service The service.
     * 
     * @return void
     */
    private function setService($service)
    {
        $this->services[get_class($service)] = $service;
    }

    /**
     * Registers the given service.
     * 
     * @param class-string<ServiceProvider> $name The service class name.
     * 
     * @throws InvalidArgumentException If the service is not valid or already registered.
     * 
     * @return void
     */
    public function register($name)
    {
        if (!class_exists($name) || !is_subclass_of($name, ServiceProvider::class)) {
            throw new InvalidArgumentException(sprintf(
                "[%s] is not a valid service.", $name
            ));
        }

        if ($this->hasService($name)) {
            throw new InvalidArgumentException(sprintf(
                "[%s] is already registered.", $name
            ));
        }

        $this->setService($service = new $name);

        if ($this->booted) {
            $service->provide();
        }
    }

    /**
     * Unregisters the given service.
     * 
     * @param class-string<ServiceProvider> $name The service class name.
     * 
     * @return void
     */
    public function unregister($name)
    {
        unset($this->services[$name]);
    }

    /**
     * Registers the base aliases.
     * 
     * @return void
     */
    private function registerBaseAliases()
    {
        // Kernel release aliases (should not be overwritten by the user)
        $this->setAlias("kernel.release.version", "1.0.0", false);
        $this->setAlias("kernel.release.name", "Alpine", false);
        $this->setAlias("kernel.release.year", "2024", false);

        // Kernel internal aliases (could be overwritten by the user)
        // Provides a more convenient way to work with the kernel during development.
        $this->setAlias("kernel.mode", "production");
    }

    /**
     * Registers the base services.
     * 
     * @return void
     */
    private function registerBaseServices()
    {
        // Base services (should not be overwritten by the user)
        // Provides the basic functionalities of the kernel.
        $this->setService(new ExceptionServiceProvider);
        $this->setService(new RoutingServiceProvider);
    }

    /**
     * Setup the registered aliases.
     * 
     * @return void
     */
    private function setupAliases()
    {
        foreach ($this->aliases as $name => $alias) {
            if ($name === "kernel.mode" && in_array($alias["value"], ["dev", "develop", "development"])) {
                $this->showErrors();
            }
        }
    }

    /**
     * Provides the registered services.
     * 
     * @return void
     */
    private function provideServices()
    {
        foreach ($this->services as $service) {
            $service->provide();
        }
    }

    /**
     * Bootstraps the kernel.
     * 
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->setupAliases();
        $this->provideServices();

        $this->booted = true;
    }
}