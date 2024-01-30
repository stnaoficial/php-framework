<?php

namespace Oraculum\FileSystem;

use Oraculum\Support\Path as PathSupport;

/**
 * @template TData
 * @template-implements File<TData>
 */
class LocalFile extends File
{
    /**
     * Creates a new instance of the class.
     * 
     * @param string $filename The filename of the file.
     * 
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = PathSupport::basePath($filename);
    }
}