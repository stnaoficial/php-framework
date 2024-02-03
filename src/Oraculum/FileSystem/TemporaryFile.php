<?php

namespace Oraculum\FileSystem;

use Oraculum\FileSystem\Exceptions\TemporaryFileException;
use Oraculum\Support\Attributes\Override;
use Throwable;

/**
 * @template TData
 * @template-implements File<TData>
 */
final class TemporaryFile extends File
{
    /**
     * Creates a new instance of the class.
     * 
     * @param string $prefix The prefix of the temporary file.
     * 
     * @return void
     */
    #[Override]
    public function __construct($prefix)
    {
        try {
            $dir = sys_get_temp_dir();

            $filename = tempnam($dir, $prefix);

            parent::__construct($filename);

        } catch (Throwable $t) {
            throw new TemporaryFileException($t->getMessage(), $t->getCode(), $t);
        }
    }
}