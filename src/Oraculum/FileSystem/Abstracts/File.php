<?php

namespace Oraculum\FileSystem\Abstracts;

use Oraculum\Support\Contracts\Stringable;
use Oraculum\Support\Primitives\PrimitiveObject;

/**
 * @template TData
 */
abstract class File extends PrimitiveObject implements Stringable
{
    /**
     * The filename of the file
     * 
     * @var string
     */
    protected $filename;

    /**
     * Creates a new instance of the class.
     * 
     * @param string $filename The filename of the file.
     * 
     * @return void
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
	 * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
	 */
	public function __toString(): string
    {
        return $this->filename;
    }

    /**
     * Gets a string representation of the object.
     * 
     * @return string Returns the `string` representation of the object.
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Gets the filename of the file.
     * 
     * @return string The filename of the file.
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Gets the real path of the file.
     * 
     * @return false|string Returns the real path of the file, or `false` on failure.
     */
    public function getRealPath()
    {
        return realpath($this->filename);
    }

    /**
     * Gets the owner of the file.
     * 
     * @return false|int Returns the owner id of the file, or `false` on failure.
     */
    public function getOwner()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return fileowner($this->filename);
    }

    /**
     * Gets the group of the file.
     * 
     * @return int Return the numerical group id of the file.
     */
    public function getGroup()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return filegroup($this->filename);
    }

    /**
     * Gets the permissions of the file.
     * 
     * @return false|int Returns the permissions of the file, or `false` on failure.
     */
    public function getPermissions()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return fileperms($this->filename);
    }

    /**
     * Gets the unix index node of the file.
     * 
     * @return false|int Return the unix index node of the file, or `false` on failure.
     */
    public function getUnixIndexNode()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return fileinode($this->filename);
    }

    /**
     * Gets the type of the file.
     * 
     * @return false|string Returns the type of the file, or `false` on failure.
     */
    public function getType()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return filetype($this->filename);
    }

    /**
     * Gets the name of the file.
     * 
     * @return string The name of the file.
     */
    public function getName()
    {
        return pathinfo($this->filename, PATHINFO_FILENAME);
    }

    /**
     * Gets the base name of the file.
     * 
     * @return string The base name of the file.
     */
    public function getBasename()
    {
        return pathinfo($this->filename, PATHINFO_BASENAME);
    }

    /**
     * Gets the directory of the file.
     * 
     * @return string The directory of the file.
     */
    public function getDirectory()
    {
        return pathinfo($this->filename, PATHINFO_DIRNAME);
    }

    /**
     * Gets the extension of the file.
     * 
     * @return string The extension of the file.
     */
    public function getExtension()
    {
        return pathinfo($this->filename, PATHINFO_EXTENSION);
    }

    /**
     * Gets the symbolic link target of the file.
     * 
     * @return false|string Returns the symbolic link target of the file, or `false` on failure.
     */
    public function getSymbolicLinkTarget()
    {
        if (!is_link($this->filename)) {
            return false;
        }

        return readlink($this->filename);
    }

    /**
     * Gets the last modified timestamp of the file.
     * 
     * @return false|int Returns the last modified timestamp of the file, or `false` on failure.
     */
    public function getLastModifiedTimestamp()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return filemtime($this->filename);
    }

    /**
     * Gets the last access timestamp of the file.
     * 
     * @return false|int Returns the last access timestamp of the file, or `false` on failure.
     */
    public function getLastAccessTimestamp()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return fileatime($this->filename);
    }

    /**
     * Checks if the file is a symbolic link.
     * 
     * @return bool Returns `true` if the file is a symbolic link, `false` otherwise.
     */
    public function isSymbolicLink()
    {
        return is_link($this->filename);
    }

    /**
     * Checks if the file is executable.
     * 
     * @return bool Returns `true` if the file is executable, `false` otherwise.
     */
    public function isExecutable()
    {
        return is_executable($this->filename);
    }

    /**
     * Checks if the file is readable.
     * 
     * @return bool Returns `true` if the file is readable, `false` otherwise.
     */
    public function isWritable()
    {
        return is_writable($this->filename);
    }

    /**
     * Checks if the file is readable.
     * 
     * @return bool Returns `true` if the file is readable, `false` otherwise.
     */
    public function isReadable()
    {
        return is_readable($this->filename);
    }

    /**
     * Checks if the file has been modified.
     * 
     * @return bool Returns `true` if the file has been modified, `false` otherwise.
     */
    public function modified()
    {
        if (!is_readable($this->filename)) {
            return false;
        }

        return filemtime($this->filename) > time();
    }

    /**
     * Checks if the file has been accessed.
     * 
     * @return bool Returns `true` if the file has been accessed, `false` otherwise.
     */
    public function accessed()
    {
        if (!is_readable($this->filename)) {
            return false;
        }
            
        return fileatime($this->filename) > time();
    }

    /**
     * Copies the file to a new location.
     * 
     * @param string $filename The filename of the new file.
     * 
     * @return bool Returns `true` on success, `false` on failure.
     */
    public function copy($filename)
    {
        return copy($this->filename, $filename);
    }
}