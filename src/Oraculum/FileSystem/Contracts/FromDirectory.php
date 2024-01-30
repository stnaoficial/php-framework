<?php

namespace Oraculum\FileSystem\Contracts;

use Oraculum\FileSystem\Directory;

interface FromDirectory
{
    /**
     * Creates a new instance from an file.
     * 
     * @param Directory $directory The directory to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromDirectory($directory);
}