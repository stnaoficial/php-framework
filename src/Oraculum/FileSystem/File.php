<?php

namespace Oraculum\FileSystem;

use Oraculum\FileSystem\Abstracts\File as AbstractFile;
use Oraculum\Support\Ob as ObSupport;

/**
 * @template TData
 * @template-implements AbstractFile<TData>
 */
class File extends AbstractFile
{
    /**
     * Gets the size of the file.
     * 
     * @return false|int Returns the size of the file, or `false` on failure.
     */
    public function getSize()
    {
        if (is_dir($this->filename) || !is_readable($this->filename)) {
            return false;
        }

        return filesize($this->filename);
    }

    /**
     * Checks if the file exists.
     * 
     * @return bool Returns `true` if the file exists, `false` otherwise.
     */
    public function exists()
    {
        return is_file($this->filename);
    }

    /**
     * Reads the contents of the file.
     * 
     * @param bool $unserialize Whether to unserialize the data.
     * 
     * @return false|TData Returns the contents of the file, or `false` on failure.
     */
    public function read($unserialize = false)
    {
        if (!is_file($this->filename)) {
            return false;
        }

        $contents = file_get_contents($this->filename);

        if (!$unserialize) {
            return $contents;
        }

        return unserialize($contents);
    }

    /**
     * Requires the file with the given variables.
     * 
     * @param array $vars   The variables to pass to the file.
     * @param bool  $buffer Whether to buffer the output.
     * 
     * @return void|string Return the contents of the file, or `void` if `$buffer` is set to `false`.
     */
    public function require($vars = [], $buffer = false)
    {
        extract($vars);

        if ($buffer) {
            ObSupport::open(); require($this->filename);
            return ObSupport::close();
        }

        require($this->filename);
    }

    /**
     * Gets the lines of the file.
     * 
     * @return array<string> The lines of the file.
     */
    public function lines()
    {
        $lines = [];

        if (!$file = fopen($this->filename, 'r')) {
            return $lines;
        }

        while ($line = fgets($file)) {
            $lines[] = $line;
        }

        fclose($file);
        
        return $lines;
    }

    /**
     * Writes the contents of the file.
     * 
     * @param TData $data      The data to write to the file.
     * @param bool  $serialize Whether to serialize the data.
     * 
     * @return false|int Returns the number of bytes written to the file, or `false` on failure.
     */
    public function write($data, $serialize = false)
    {
        $dir = dirname($this->filename);

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
        }

        if ($serialize) {
            $data = serialize($data);
        }

        return file_put_contents($this->filename, $data,
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * Clears the file.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function clear()
    {
        if (!is_writable($this->filename)) {
            return false;
        }
        
        $stream = fopen($this->filename, 'w');

        $cleared = true;

        if (!ftruncate($stream, 0)) {
            $cleared = false;
        }

        if (!fclose($stream)) {
            $cleared = false;
        }

        return $cleared;
    }

    /**
     * Deletes the file.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function delete()
    {
        if (!is_file($this->filename)) {
            return false;
        }

        return unlink($this->filename);
    }
}