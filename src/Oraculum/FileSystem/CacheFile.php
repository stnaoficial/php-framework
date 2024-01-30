<?php

namespace Oraculum\FileSystem;

use Oraculum\Support\Path as PathSupport;

/**
 * @template TData
 * @template-implements File<TData>
 */
final class CacheFile extends File
{
    /**
     * Creates a new instance of the class.
     * 
     * @param string $name The name of the file cache.
     * 
     * @return void
     */
    public function __construct($name)
    {
        $hash = md5($name);

        $this->filename = PathSupport::join(__STORAGE_DIR__, "cache", $hash);
    }

    /**
     * Memorizes the data.
     * 
     * @param TData $data The data to memorize.
     * 
     * @return TData The memorized data.
     */
    public function memorize($data)
    {
        if ($this->exists()) {
            return $this->read(true);
        }

        if (is_callable($data)) {
            $data = $data();
        }

        $this->write($data, true);

        return $data;
    }
}