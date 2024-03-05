<?php

namespace Miscellaneous\Autoloader;

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
     * @var File $file The actual file to autoload.
     */
    private $file;

    /**
     * @var array $resolve The options resolved by the autoloader.
     */
    private $resolve = [
        'php'   => PHP_VERSION,
        'files' => [],
        'psr-4' => []
    ];

    /**
     * @var bool $resolved Whether the module has been resolved.
     */
    private $resolved = false;

    /**
     * @var array<string> $dependencies All autoloaded dependencies.
     */
    private $dependencies = [];

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
                "File %s does not exist.", $this->file->getFilename()
            ));
        }
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
     * Binds options to the autoloader.
     *
     * @param array $options The options to bind.
     * 
     * @throws UnexpectedValueException If the PHP version is not set or too old.
     *
     * @return void
     */
    private function bindOptions($options)
    {
        if (!isset($options['php'])) {
            throw new UnexpectedValueException(sprintf(
                "The autoloader requires a version option to be set."
            ));
        }

        if (version_compare(PHP_VERSION, $options['php'], '<')) {
            throw new UnexpectedValueException(sprintf(
                'The autoloader requires PHP version %s or higher. Getting %s.', $options['php'], PHP_VERSION
            ));
        }

        $this->resolve['php'] = $options['php'];

        if (isset($options['files'])) {
            foreach ($options['files'] as $filename) {
                $this->resolve['files'][] = PathSupport::join($this->file->getDirectory(), $filename);
            }
        }

        if (isset($options['psr-4'])) {
            foreach ($options['psr-4'] as $namespace => $path) {
                $this->resolve['psr-4'][$namespace] = PathSupport::join($this->file->getDirectory(), $path);
            }
        }
    }

    /**
     * Binds options recursively to the autoloader.
     *
     * @param string $filename The autoload filename to get the options from.
     * 
     * @throws InvalidArgumentException If some autoload file does match the requirements.
     * @throws UnexpectedValueException If the PHP version is not set or too old.
     *
     * @return void
     */
    private function bindOptionsRecursively($filename)
    {   
        // We are using the parent directory as the root directory for the
        // currently binding file.
        $filename = PathSupport::join($this->file->getDirectory(), $filename);

        $this->file = new File($filename);

        if (!$this->file->exists()) {
            throw new InvalidArgumentException(sprintf(
                "File %s does not exist.", $filename
            ));
        }

        if ($this->file->getBasename() !== 'autoload.json') {
            throw new InvalidArgumentException(sprintf(
                "%s is not a valid autoload file name.", $filename
            ));
        }

        if (!$contents = $this->file->read()) {
            throw new InvalidArgumentException(sprintf(
                "Unable to get the contents of file %s.", $filename
            ));
        }

        // Bind the options from the autoload file.
        $this->bindOptions($options = Json::new($contents)->toArray());

        if (!isset($options['extends'])) {
            return;
        }

        // Extends a parent autoload file options recursively.
        $this->bindOptionsRecursively($options['extends']);
    }

    /**
     * Build dependencies.
     *
     * @throws InvalidArgumentException If some autoload file does match the requirements.
     * @throws UnexpectedValueException If the PHP version is not set or too old.
     *
     * @return void
     */
    private function build()
    {
        // We must initialize binding the options by the basename of the file
        // because its the root autoload file.
        $this->bindOptionsRecursively($this->file->getBasename());
    }

    /**
     * Ignores a dependency.
     *
     * @param string $filename The filename of the dependency to ignore.
     *
     * @return void
     */
    public function ignore($filename)
    {
        $this->dependencies[$filename] = $filename;
    }

    /**
     * Loads the given dependency.
     * 
     * @param string $filename The filename of the dependency to load.
     * 
     * @throws InvalidArgumentException If the file does not exist.
     * 
     * @return void
     */
    public function load($filename)
    {
        // Skips the file if it is already loaded.
        // This prevent the autoloader from loading the same file multiple times.
        if (isset($this->dependencies[$filename])) {
            return;
        }

        // Throw an exception if the file does not exist.
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf(
                "File %s does not exist.", $filename
            ));
        }

        // Loads the file and adds it to the list of loaded dependencies.
        require_once $this->dependencies[$filename] = $filename;
    }

    /**
     * Load all given dependencies.
     * 
     * @param array $filenames The filename of the dependencies to load.
     * 
     * @throws InvalidArgumentException If some file does not exist.
     * 
     * @return void
     */
    public function loadAll($filenames)
    {
        foreach ($filenames as $filename) {
            $this->load($filename);
        }
    }

    /**
     * Automatically load classes.
     *
     * @param string $class The name of the class.
     * 
     * @throws InvalidArgumentException If the class cannot be autoloaded.
     *
     * @return void
     */
    private function autoloader($class)
    {
        // Applies the PSR-4 autoloading rules to the class.
        // Check if the class name starts with the current namespace.
        // If so, replace it with the full path to the file.
        // e.g. App\Example\Class -> /app\Example\Class
        foreach ($this->resolve['psr-4'] as $namespace => $path) {
            if (str_starts_with($class, $namespace)) {
                $class = str_replace($namespace, $path . DIRECTORY_SEPARATOR, $class);
                break;
            }
        }

        // Replaces the namespace separator with a directory separator in the 
        // fully-qualified class name.
        // e.g. /app\Example\Class -> /app/Example/Class.php
        // It also adds the PHP file extension.
        $filename = str_replace(NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class) . PHP_FILE_EXTENSION;

        $this->load($filename);
    }

    /**
     * Resolve dependencies.
     *
     * @throws InvalidArgumentException If some file does not exist.
     * 
     * @return void
     */
    private function resolve()
    {
        if ($this->resolved) {
            return;
        }

        foreach ($this->resolve['files'] as $filename) {
            $this->load($filename);
        }

        spl_autoload_register([$this, 'autoloader']);

        $this->resolved = true;
    }

    /**
     * Starts the autoloader.
     * 
     * @param bool $rebuild Whether to rebuild the autoload file.
     *
     * @throws InvalidArgumentException If some autoload file does match the requirements.
     * @throws UnexpectedValueException If the PHP version is not set or too old.
     * 
     * @return void
     */
    public function autoload($rebuild = false)
    {
        $cache = new CacheFile(self::class);

        if ($rebuild || !$cache->exists()) {
            $this->build();
        }

        // Get the build options from the cache memoization.
        // It considers the already computed options from the cache if they exist,
        // otherwise it build the options again and stores them in the cache.
        $this->resolve = $cache->memorize($this->resolve);

        $this->resolve();
    }
}
