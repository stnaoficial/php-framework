<?php

namespace Oraculum\Stub;

use Oraculum\FileSystem\File;
use Oraculum\Http\Exceptions\InvalidStubException;
use Oraculum\Support\Primitives\PrimitiveObject;

final class Stub extends PrimitiveObject
{
    const FIELD_MATCH_PATTERN = '/\{\{\s*([^}|\s]+)\s*\}\}/';

    /**
     * @var string The source of the stub.
     */
    private $source;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $source The source of the stub.
     * 
     * @return void
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * Creates a new instance of the class for the given stub name.
     * 
     * @param string $name The name of the stub.
     * 
     * @return self
     */
    private static function of($name)
    {
        $source = File::new(__DIR__ . "/resources/stubs/{$name}.stub")->read();

        return new self($source);
    }

    /**
     * Creates a new instance of the class for a PHP class stub.
     * 
     * @return self
     */
    public static function ofPhpClass()
    {
        return self::of('php-class');
    }

    /**
     * Creates a new instance of the class for a PHP interface stub.
     * 
     * @return self
     */
    public static function ofPhpInterface()
    {
        return self::of('php-interface');
    }

    /**
     * Fills the stub with the given fields.
     * 
     * @param array<string, string> $fields The fields to fill in.
     * 
     * @throws InvalidStubException If a field is missing.
     * 
     * @return string The filled stub.
     */
    public function fill($fields = [])
    {
        $filled = $this->source;

        preg_match_all(self::FIELD_MATCH_PATTERN, $filled, $matches);

        if (empty($matches)) {
            return $filled;
        }

        foreach ($matches[1] as $key => $fieldName) {
            $fieldOccur = $matches[0][$key];

            if (!isset($fields[$fieldName])) {
                throw new InvalidStubException(sprintf(
                    "Missing [%s] field.", $fieldName
                ));
            }

            $filled = str_replace($fieldOccur, $fields[$fieldName], $filled);
        }

        return $filled;
    }
}