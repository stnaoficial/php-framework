<?php

namespace Oraculum\FileSystem;

use Oraculum\Support\Attributes\Override;
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
    #[Override]
    public function __construct($filename)
    {
        $filename = PathSupport::basePath($filename);
    
        parent::__construct($filename);
    }
}