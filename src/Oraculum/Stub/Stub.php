<?php

namespace Oraculum\Stub;

use InvalidArgumentException;
use Oraculum\FileSystem\Contracts\FromFile;
use Oraculum\FileSystem\File;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Stub extends PrimitiveObject implements FromFile
{
    /**
     * The stub source.
     * 
     * @var string
     */
    private $source;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $source The stub source.
     * 
     * @return void
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Creates a new instance from an file.
     * 
     * @param File $file The file to create the instance.
     * 
     * @return self The new instance.
     */
    public static function fromFile($file)
    {
        if ($file->getExtension() !== 'stub') {
            throw new InvalidArgumentException(sprintf(
                "%s is not a valid stub file.", $file->getFilename()
            ));
        }

        return new self($file->read());
    }

    /**
     * Computes the stub.
     * 
     * @param array $data The data to compute.
     * 
     * @return string The computed stub.
     */
    public function compute($data = [])
    {
        $vars = [];

        foreach ($data as $key => $value) {
            $vars['{{' . $key . '}}'] = $value;
        }

        return strtr($this->source, $vars);
    }
}