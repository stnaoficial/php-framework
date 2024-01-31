<?php

namespace Oraculum\FileSystem;

use Oraculum\Support\Path as PathSupport;

/**
 * @template TData
 * @template-implements ReadonlyFile<TData>
 */
final class CacheFile extends ReadonlyFile
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

        // Attaches the cache directory.
        // e.g. /storage/cache
        $filename = PathSupport::join(__STORAGE_DIR__, "cache", $hash);

        parent::__construct($filename);
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
            return unserialize($this->read());
        }

        if (is_callable($data)) {
            $data = $data();
        }

        $this->write(serialize($data));

        return $data;
    }
}