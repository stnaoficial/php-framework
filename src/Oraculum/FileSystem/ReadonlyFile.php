<?php

namespace Oraculum\FileSystem;

use Oraculum\FileSystem\Abstracts\File as AbstractFile;

/**
 * @template TData
 * @template-implements AbstractFile<TData>
 */
class ReadonlyFile extends AbstractFile
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
     * Reads the contents of the file.
     * 
     * @return false|TData Returns the contents of the file, or `false` on failure.
     */
    public function read()
    {
        if (!is_file($this->filename)) {
            return false;
        }

        return file_get_contents($this->filename);
    }

    /**
     * Writes the contents of the file.
     * 
     * @param TData $data      The data to write to the file.
     * 
     * @return false|int Returns the number of bytes written to the file, or `false` on failure.
     */
    protected function write($data)
    {
        $dir = dirname($this->filename);

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
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
    protected function clear()
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
    protected function delete()
    {
        if (!is_file($this->filename)) {
            return false;
        }

        return unlink($this->filename);
    }

    /**
     * Requires the file with the given variables.
     * 
     * @param array $vars The variables to pass to the file.
     * 
     * @return TData Returns the contents of the file.
     */
    public function require($vars = [])
    {
        extract($vars);

        return require($this->filename);
    }
}