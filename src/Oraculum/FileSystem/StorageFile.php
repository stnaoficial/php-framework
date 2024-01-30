<?php

namespace Oraculum\FileSystem;

use Oraculum\Support\Path as PathSupport;

/**
 * @template TData
 * @template-implements File<TData>
 */
final class StorageFile extends File
{
    /**
     * Creates a new instance of the class.
     * 
     * @param string      $name   The name of the file to store.
     * @param string|null $format The format of the file.
     * 
     * @return void
     */
    public function __construct($name, $format = null)
    {
        $hash = md5($name) . (is_null($format)? '' : $format);

        $this->filename = PathSupport::join(__STORAGE_DIR__, $hash);
    }

    /**
     * Store the data.
     * 
     * @param TData $data The data to store.
     * 
     * @return TData The stored data.
     */
    public function store($data)
    {
        if ($this->exists()) {
            return $this->read();
        }

        if (is_callable($data)) {
            $data = $data();
        }

        $this->write($data);

        return $data;
    }
}