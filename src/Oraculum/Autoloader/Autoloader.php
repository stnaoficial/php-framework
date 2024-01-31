<?php

namespace Oraculum\Autoloader;

use InvalidArgumentException;
use Oraculum\FileSystem\CacheFile;
use Oraculum\FileSystem\File;
use Oraculum\Json\Json;
use Oraculum\Support\Attributes\Override;
use Oraculum\Support\Path as PathSupport;
use Oraculum\Support\Primitives\PrimitiveObject;
use Oraculum\Support\Traits\GloballyAvailable;
use UnexpectedValueException;

/**
 * @template T of static
 */
final class Autoloader extends PrimitiveObject
{
    use GloballyAvailable;

    /**
     * @var File The actual entry file to autoload.
     */
    private $file;

    /**
     * @var array<string> The required files.
     */
    private $requiredFiles = [];

    /**
     * @var array<string, string> The namespace resolutions.
     */
    private $namespaceResolutions = [];

    /**
     * @var array<string> All autoloaded dependencies.
     */
    private $dependencies = [];

    /**
     * @var bool Whether the module has been resolved.
     */
    private $resolved = false;

    /**
     * Creates a new instance of the class.
     *
     * @param string $filename The entry file which all dependencies are bound.
     *
     * @return void
     */
    public function __construct($filename)
    {
        $this->file = new File($filename);

        if (!$this->file->exists()) {
            throw new InvalidArgumentException(sprintf(
                "File %s does not exist.",
                $this->file
            ));
        }

        $this->setInstance($this);
    }

    /**
     * Gets the shared instance.
     *
     * @throws UnexpectedValueException If the instance has not been properly initialized.
     *
     * @return T The shared instance.
     */
    #[Override]
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            throw new UnexpectedValueException(sprintf(
                'The %s instance needs to be properly initialized first.', Autoloader::class
            ));
        }

        return self::$instance;
    }

    /**
     * Sets the computed options.
     *
     * @param array $options The computed options to set.
     *
     * @return void
     */
    private function setComputedOptions($options)
    {
        $this->requiredFiles        = $options['require'];
        $this->namespaceResolutions = $options['resolve'];
    }

    /**
     * Gets the computed options.
     *
     * @return array{require: array<string>, resolve: array<string, string>} The computed options.
     */
    private function getComputedOptions()
    {
        return [
            'require' => $this->requiredFiles,
            'resolve' => $this->namespaceResolutions
        ];
    }

    /**
     * Binds options to the autoloader.
     *
     * @param array $options The options to bind.
     *
     * @return void
     */
    private function bindOptions($options)
    {
        if (isset($options['require'])) {
            foreach ($options['require'] as $filename) {
                $this->requiredFiles[] = PathSupport::join($this->file->getDirectory(), $filename);
            }
        }

        if (isset($options['resolve'])) {
            foreach ($options['resolve'] as $namespace => $path) {
                $this->namespaceResolutions[$namespace] = PathSupport::join($this->file->getDirectory(), $path);
            }
        }
    }

    /**
     * Binds options recursively to the autoloader.
     *
     * @param string $filename The filename to get the options from.
     *
     * @return void
     */
    private function bindOptionsRecursively($filename)
    {
        $filename = PathSupport::join($this->file->getDirectory(), $filename);

        $this->file = new File($filename);

        if (!$this->file->exists()) {
            throw new InvalidArgumentException(sprintf(
                "File %s does not exist.",
                $this->file
            ));
        }

        if (!$contents = $this->file->read()) {
            throw new InvalidArgumentException(sprintf(
                "Unable to get the contents of file %s.",
                $this->file
            ));
        }

        $this->bindOptions($options = Json::new($contents)->toArray());

        if (!isset($options['extends'])) {
            return;
        }

        $this->bindOptionsRecursively($options['extends']);
    }

    /**
     * Build dependencies.
     *
     * @throws InvalidArgumentException If be unable to get file contents.
     * @throws UnexpectedValueException If be unable to decode.
     *
     * @return void
     */
    private function build()
    {
        $this->bindOptionsRecursively($this->file->getBasename());
    }

    /**
     * Require file dependencies.
     *
     * @return void
     */
    private function requireFiles()
    {
        foreach ($this->requiredFiles as $filename) {
            if (isset($this->dependencies[$filename])) {
                continue;
            }

            require_once $this->dependencies[$filename] = $filename;
        }
    }

    /**
     * Automatically loads classes.
     *
     * @param string $class The name of the class.
     *
     * @return bool Returns true on success or false on failure.
     */
    private function autoloader($class)
    {
        foreach ($this->namespaceResolutions as $namespace => $path) {
            if (str_starts_with($class, $namespace)) {
                $class = str_replace($namespace, $path . DIRECTORY_SEPARATOR, $class);
                break;
            }
        }

        $filename = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class) . PHP_FILE_EXTENSION;

        if (file_exists($filename) && !isset($this->dependencies[$filename])) {
            require_once $this->dependencies[$filename] = $filename;
            return true;
        }

        return false;
    }

    /**
     * Register the autoloader.
     *
     * @return void
     */
    private function registerAutoloader()
    {
        spl_autoload_register([$this, 'autoloader']);
    }

    /**
     * Resolve dependencies.
     *
     * @return void
     */
    private function resolve()
    {
        if ($this->resolved) {
            return;
        }

        $this->requireFiles();
        $this->registerAutoloader();

        $this->resolved = true;
    }

    /**
     * Ignores a dependency.
     *
     * @param string $filename The filename to ignore.
     *
     * @return void
     */
    public function ignore($filename)
    {
        $this->dependencies[$filename] = $filename;
    }

    /**
     * Starts the autoloader.
     *
     * @return void
     */
    public function autoload()
    {
        $cache = new CacheFile(self::class);

        if (!$cache->exists()) {
            $this->build();
        }

        // Get the computed options from the cache memoization.
        // It considers the already computed options from the cache if they exist,
        // otherwise it computes the options again and stores them in the cache.
        // We are also typing the options for a better usage.
        /** @var array{require:array<string>, resolve:array<string, string>} */
        $options = $cache->memorize(function() {
            return $this->getComputedOptions();
        });

        $this->setComputedOptions($options);

        $this->resolve();
    }
}
