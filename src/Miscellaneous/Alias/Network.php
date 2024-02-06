<?php

namespace Miscellaneous\Alias;

use Oraculum\Alias\Network as BaseNetwork;
use Oraculum\FileSystem\Contracts\FromFile;
use Oraculum\FileSystem\File;

final class Network extends BaseNetwork implements FromFile
{
    /**
     * Creates a new instance from an file.
     * 
     * @param File $file The file to create the instance.
     * 
     * @throws InvalidArgumentException If some alias is not valid.
     * 
     * @return self The new instance.
     */
    public static function fromFile($file)
    {
        $aliases = $file->require();

        return new self($aliases);
    }
}