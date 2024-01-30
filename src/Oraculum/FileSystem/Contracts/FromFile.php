<?php

namespace Oraculum\FileSystem\Contracts;

use Oraculum\FileSystem\File;

interface FromFile
{
    /**
     * Creates a new instance from an file.
     * 
     * @param File $file The file to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromFile($file);
}