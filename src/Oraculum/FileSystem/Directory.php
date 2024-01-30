<?php

namespace Oraculum\FileSystem;

use Oraculum\FileSystem\Abstracts\File as AbstractFile;

/**
 * @template TData
 * @template-implements AbstractFile<TData>
 */
final class Directory extends AbstractFile
{       
    /**
     * Gets the size of the given filename recursively.
     * 
     * @param string $filename The filename to get the size of.
     * 
     * @return int Returns the size of the filename recursively.
     */
    private function getSizeRecursively($filename)
    {
        $size = 0;

        $files = glob($filename . '/*');

        foreach ($files as $filename) {
            is_dir($filename)  && $size += $this->getSizeRecursively($filename);
            is_file($filename) && $size += filesize($filename);
        }

        return $size;
    }

    /**
     * Gets the size of the directory.
     * 
     * @return false|int Returns the size of the directory, or `false` on failure.
     */
    public function getSize()
    {        
        if (!is_dir($this->filename)) {
            return false;
        }
        
        return $this->getSizeRecursively($this->filename);
    }

    /**
     * Checks if the directory exists.
     * 
     * @return bool Returns `true` if the directory exists, `false` otherwise.
     */
    public function exists()
    {
        return is_dir($this->filename);
    }

    /**
     * Deletes the directory recursively.
     * 
     * @param string $filename The filename to delete.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    private function deleteRecursively($filename)
    {
        $archives = glob($filename . '/*');

        if (count($archives) === 0) {
            return false;
        }

        foreach ($archives as $archive) {
            is_dir($archive) && $this->deleteRecursively($archive);
            is_file($archive) && unlink($archive);
        }

        return true;
    }

    /**
     * Clears the directory.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function clear()
    {
        if (!is_dir($this->filename)) {
            return false;
        }

        return $this->deleteRecursively($this->filename);
    }

    /**
     * Deletes the directory.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function delete()
    {
        if (!is_dir($this->filename)) {
            return false;
        }

        $this->deleteRecursively($this->filename);

        return rmdir($this->filename);
    }

    /**
     * Creates the directory.
     * 
     * @param int $permissions The permissions of the directory.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function create($permissions = 0777)
    {
        if (is_dir($this->filename)) {
            return false;
        }

        return mkdir($this->filename, $permissions, true);
    }
}