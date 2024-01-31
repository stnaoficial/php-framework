<?php

namespace Oraculum\FileSystem;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * @template TData
 * @template-implements ReadonlyFile<TData>
 */
class StubFile extends ReadonlyFile
{
    const FIELD_MATCH_PATTERN = '/\{\{\s*([^}|\s]+)\s*\}\}/';

    /**
     * Creates a new instance of the class.
     * 
     * @param string $filename The filename of the file.
     * 
     * @return void
     */
    public function __construct($filename)
    {
        parent::__construct($filename);

        if ($this->getExtension() !== 'stub') {
            throw new InvalidArgumentException(sprintf(
                "%s is not a valid stub file.", $filename
            ));
        }
    }

    /**
     * Fills the stub file with the given fields.
     * 
     * @param array<string, string> $fields The fields to fill in.
     * 
     * @throws UnexpectedValueException If a field is missing.
     * 
     * @return string The filled stub file.
     */
    public function fill($fields = [])
    {
        $contents = $this->read();

        preg_match_all(self::FIELD_MATCH_PATTERN, $contents, $matches);

        if (empty($matches)) {
            return $contents;
        }

        foreach ($matches[1] as $key => $fieldName) {
            $fieldOccur = $matches[0][$key];

            if (!isset($fields[$fieldName])) {
                throw new UnexpectedValueException(sprintf(
                    "Missing [%s] field.", $fieldName
                ));
            }

            $contents = str_replace($fieldOccur, $fields[$fieldName], $contents);
        }

        return $contents;
    }

    /**
     * Clones the file with the given fields.
     * 
     * @param string                 $filename The filename of the file.
     * @param array<string, string>  $fields   The fields to fill in.
     *
     * @throws UnexpectedValueException If a field is missing.
     *
     * @return File The cloned file.
     */
    public function clone($filename, $fields = [])
    {
        $file = new File($filename);

        $file->clear();

        $file->write($this->fill($fields));

        return $file;
    }
}