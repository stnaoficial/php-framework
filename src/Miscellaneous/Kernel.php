<?php

namespace Miscellaneous;

use InvalidArgumentException;
use Miscellaneous\Abstracts\ServiceProvider;
use Miscellaneous\Alias\Network as AliasNetwork;
use Oraculum\FileSystem\File;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use Oraculum\Support\Traits\NonInstantiable;

final class Kernel extends PrimitiveObject
{
    use NonInstantiable, GloballyAvailable;

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
        $this->registerBaseAliasNetwork();
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
     * Setup the given alias.
     * 
     * @param \Oraculum\Alias\Alias $alias The alias to setup.
     * 
     * @return void
     */
    private function setupAlias($alias)
    {
        switch ($alias->getName()) {
            case 'mode':
                in_array($alias->getValue(), ["dev", "develop", "development"]) && $this->showErrors();
                break;
            case 'boot.files':
                foreach ($alias->getValue() as $filename) {
                    require_once $filename;
                }
                break;
            default:
                break;
        }
    }

    /**
     * Setup the registered aliases.
     * 
     * @return void
     */
    private function setupAliases()
    {
        foreach (AliasNetwork::getInstance()->getAliases() as $alias) {
            $this->setupAlias($alias);
        }
    }

    /**
     * Registers the base alias network.
     * 
     * @throws InvalidArgumentException If some alias is not valid.
     * 
     * @return void
     */
    private function registerBaseAliasNetwork()
    {
        $file = new File(__DIR__ . "/resources/aliases.php");

        // Changes the global alias network instance.
        // This makes the remaining aliases work as expected.
        AliasNetwork::setInstance(AliasNetwork::fromFile($file));
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
     * Registers the base services.
     * 
     * @return void
     */
    private function registerBaseServices()
    {
        // Base services (should not be overwritten by the user)
        // Provides the basic functionalities of the kernel.
        $this->setService(new ExceptionHandlingServiceProvider);
        $this->setService(new RoutingServiceProvider);
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